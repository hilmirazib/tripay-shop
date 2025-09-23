<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { z } from 'zod'

const props = defineProps({
  product: {
    type: Object,
    required: true,
    validator: (p) =>
      p &&
      typeof p.id === 'number' &&
      typeof p.name === 'string' &&
      typeof p.sku === 'string' &&
      typeof p.price === 'number'
  },
  methods: {
    type: Array,
    default: () => []
  }
})

const schema = z.object({
  product_id: z.number().int().positive(),
  buyer_email: z.string().email(),
  buyer_phone: z.string().min(6),
  payment_method: z.string().min(1),
})

const form = useForm({
  product_id: props.product.id,
  buyer_email: '',
  buyer_phone: '',
  payment_method: props.methods[0]?.code ?? '',
})

function submit() {
  const parsed = schema.safeParse(form.data())
  if (!parsed.success) {
    alert('Periksa input: ' + parsed.error.issues.map(i => i.message).join(', '))
    return
  }
  form.post('/checkout')
}
</script>

<template>
  <Head :title="`Checkout ${product.name}`" />
  <main class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Checkout</h1>

    <!-- product card -->
    <section class="border border-gray-200 rounded-lg p-4 mb-4">
      <div class="font-semibold text-lg">{{ product.name }}</div>
      <div class="text-sm text-gray-500">SKU: {{ product.sku }}</div>
      <div class="mt-1">Harga: Rp {{ product.price.toLocaleString('id-ID') }}</div>
    </section>

    <form @submit.prevent="submit" class="grid gap-4">
      <!-- metode -->
      <label class="block">
        <span class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</span>
        <select
          v-model="form.payment_method"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
          <option
            v-for="m in methods"
            :key="m.code"
            :value="m.code"
          >
            {{ m.name }}
          </option>
        </select>
      </label>

      <!-- email -->
      <input
        v-model="form.buyer_email"
        type="email"
        placeholder="Email Anda"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2"
      />

      <!-- phone -->
      <input
        v-model="form.buyer_phone"
        placeholder="No HP Anda"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2"
      />

      <!-- actions -->
      <div class="flex gap-2">
        <button
          type="submit"
          :disabled="form.processing"
          class="px-4 py-2 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 disabled:opacity-50"
        >
          Lanjutkan
        </button>
        <Link
          href="/"
          as="button"
          class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300"
        >
          Batal
        </Link>
      </div>

      <!-- error -->
      <div v-if="Object.keys(form.errors).length" class="text-red-600 text-sm">
        <div
          v-for="(msg, key) in form.errors"
          :key="key"
        >
          {{ key }}: {{ msg }}
        </div>
      </div>
    </form>
  </main>
</template>
