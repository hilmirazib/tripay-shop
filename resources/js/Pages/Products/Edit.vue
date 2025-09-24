<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const initial = {
  sku: props.product.sku ?? '',
  name: props.product.name ?? '',
  price: props.product.price ?? 0,
  reference: props.product.reference ?? ''
}

const form = useForm({ ...initial })

function submit() {
  form.put(`/admin/products/${props.product.id}`)
}

function resetChanges() {
  form.reset()
  form.sku = initial.sku
  form.name = initial.name
  form.price = initial.price
  form.reference = initial.reference
}

const formatIDR = (n) => (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })
</script>

<template>
  <AppLayout>
    <Head title="Edit Product" />

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">Edit Product</h1>
        <Link
          href="/admin/products"
          class="rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
        >
          Back
        </Link>
      </div>

      <form @submit.prevent="submit" class="rounded-2xl border border-slate-200 bg-white p-5 space-y-5">
        <!-- SKU -->
        <div>
          <label for="sku" class="text-sm font-medium">SKU</label>
          <input
            id="sku"
            v-model="form.sku"
            placeholder="SKU unik, mis. SKU-QRIS-001"
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 font-mono"
          />
          <p class="mt-1 text-xs text-slate-500">
            Gunakan format konsisten agar mudah dicari.
          </p>
          <p v-if="form.errors.sku" class="mt-1 text-sm text-red-600">{{ form.errors.sku }}</p>
        </div>

        <!-- Name -->
        <div>
          <label for="name" class="text-sm font-medium">Name</label>
          <input
            id="name"
            v-model="form.name"
            placeholder="Nama produk yang tampil ke customer"
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
          />
          <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>

        <!-- Price -->
        <div>
          <label for="price" class="text-sm font-medium">Price (IDR)</label>
          <input
            id="price"
            v-model.number="form.price"
            type="number"
            min="0"
            step="1"
            inputmode="numeric"
            placeholder="0"
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
          />
          <p class="mt-1 text-xs text-slate-500">
            Saat ini: <span class="font-medium">Rp {{ formatIDR(form.price) }}</span>
          </p>
          <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
        </div>

        <div>
          <label for="reference" class="text-sm font-medium">Reference (TriPay)</label>
          <input
            id="reference"
            v-model="form.reference"
            placeholder="Mis. BRIVA-25K, DANA-50K, dsb"
            class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300 font-mono"
          />
          <p class="mt-1 text-xs text-slate-500">
            Opsional—untuk mapping internal/sku ke katalog eksternal/gateway.
          </p>
          <p v-if="form.errors.reference" class="mt-1 text-sm text-red-600">{{ form.errors.reference }}</p>
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap items-center gap-2 pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800 disabled:opacity-50"
          >
            {{ form.processing ? 'Saving…' : 'Update' }}
          </button>
          <button
            type="button"
            @click="resetChanges"
            class="rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
          >
            Reset
          </button>
          <Link
            href="/admin/products"
            class="text-sm text-slate-600 hover:underline"
          >
            Cancel
          </Link>
        </div>

        <!-- Error list (fallback) -->
        <div
          v-if="form.errors && Object.keys(form.errors).length"
          class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700"
        >
          <div v-for="(msg, key) in form.errors" :key="key">
            {{ key }}: {{ msg }}
          </div>
        </div>
      </form>
    </main>
  </AppLayout>
</template>
