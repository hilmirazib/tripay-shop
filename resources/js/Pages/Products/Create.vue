<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
const form = useForm({ sku:'', name:'', price:0, reference:'' })
function submit(){ form.post('/admin/products') }
</script>

<template>
  <Head title="Create Product" />
  <main class="px-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create Product</h1>

    <form @submit.prevent="submit" class="grid gap-4">
      <div>
        <input
          v-model="form.sku"
          placeholder="SKU"
          class="w-full border rounded px-3 py-2"
        />
        <p v-if="form.errors?.sku" class="text-red-600 text-sm mt-1">
          {{ form.errors.sku }}
        </p>
      </div>

      <div>
        <input
          v-model="form.name"
          placeholder="Name"
          class="w-full border rounded px-3 py-2"
        />
        <p v-if="form.errors?.name" class="text-red-600 text-sm mt-1">
          {{ form.errors.name }}
        </p>
      </div>

      <div>
        <input
          v-model.number="form.price"
          type="number"
          min="0"
          placeholder="Price (IDR)"
          class="w-full border rounded px-3 py-2"
        />
        <p v-if="form.errors?.price" class="text-red-600 text-sm mt-1">
          {{ form.errors.price }}
        </p>
      </div>

      <div>
        <input
          v-model="form.reference"
          placeholder="Reference (TriPay)"
          class="w-full border rounded px-3 py-2"
        />
        <p v-if="form.errors?.reference" class="text-red-600 text-sm mt-1">
          {{ form.errors.reference }}
        </p>
      </div>

      <div class="flex gap-2">
        <button
          :disabled="form.processing"
          class="bg-black text-white px-4 py-2 rounded disabled:opacity-50 disabled:pointer-events-none"
        >
          Save
        </button>
        <Link href="/admin/products" class="px-4 py-2 border rounded">
          Cancel
        </Link>
      </div>

      <div
        v-if="form.errors && Object.keys(form.errors).length"
        class="text-red-600 text-sm"
      >
        <div
          v-for="(msg, key) in form.errors"
          :key="key"
          v-show="!['sku','name','price','reference'].includes(key)"
        >
          {{ key }}: {{ msg }}
        </div>
      </div>
    </form>
  </main>
</template>
