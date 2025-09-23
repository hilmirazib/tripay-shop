<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  product: Object
})

const form = useForm({
  sku: props.product.sku,
  name: props.product.name,
  price: props.product.price,
  reference: props.product.reference ?? ''
})

function submit() {
  form.put(`/admin/products/${props.product.id}`)
}
</script>


<template>
  <Head title="Edit Product" />
  <main class="px-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

    <form @submit.prevent="submit" class="grid gap-4">
      <input
        v-model="form.sku"
        placeholder="SKU"
        class="w-full border rounded px-3 py-2"
      />

      <input
        v-model="form.name"
        placeholder="Name"
        class="w-full border rounded px-3 py-2"
      />

      <input
        v-model.number="form.price"
        type="number"
        min="0"
        placeholder="Price (IDR)"
        class="w-full border rounded px-3 py-2"
      />

      <input
        v-model="form.reference"
        placeholder="Reference (TriPay)"
        class="w-full border rounded px-3 py-2"
      />

      <div class="flex gap-2">
        <button
          :disabled="form.processing"
          class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50 disabled:pointer-events-none"
        >
          Update
        </button>
        <Link href="/admin/products" class="px-4 py-2 border rounded">
          Back
        </Link>
      </div>

      <div
        v-if="form.errors && Object.keys(form.errors).length"
        class="text-red-600 text-sm"
      >
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
