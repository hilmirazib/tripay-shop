<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  filters: Object,
  products: Object
})
</script>

<template>
  <Head title="Home" />
  <main class="px-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-3">Produk</h1>

    <form method="get" class="mb-3 flex gap-2">
      <input
        name="q"
        :value="filters?.q ?? ''"
        placeholder="Cari nama / SKU"
        class="border rounded px-3 py-2 flex-1"
      />
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Cari
      </button>
    </form>

    <ul class="grid gap-3 list-none p-0">
      <li
        v-for="p in products.data"
        :key="p.id"
        class="border border-gray-200 p-3 rounded-lg flex justify-between items-center"
      >
        <div>
          <div class="font-semibold">{{ p.name }}</div>
          <div class="text-xs text-gray-500">SKU: {{ p.sku }}</div>
          <div class="mt-1">Rp {{ (p.price).toLocaleString('id-ID') }}</div>
        </div>
        <Link
          :href="`/checkout/start?product_id=${p.id}`"
          as="button"
          class="bg-green-600 text-white px-4 py-2 rounded"
        >
          Beli
        </Link>
      </li>
    </ul>

    <nav class="mt-4 flex gap-2 flex-wrap">
      <Link
        v-for="l in products.links"
        :key="l.url"
        :href="l.url || '#'"
        v-html="l.label"
        :class="[
          'px-3 py-1 border rounded',
          l.active ? 'font-bold underline border-blue-500' : 'border-gray-200',
          !l.url && 'opacity-50 pointer-events-none'
        ]"
      />
    </nav>
  </main>
</template>
