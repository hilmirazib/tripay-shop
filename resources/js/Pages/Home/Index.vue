<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import FlashToast from '@/Components/FlashToast.vue'

const props = defineProps({
  filters: { type: Object, default: () => ({}) },
  products: { type: Object, required: true } // Laravel paginator: { data, links, ... }
})

const formatIDR = (n) =>
  (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })
</script>

<template>
  <AppLayout>
    <Head title="Home" />
    <FlashToast />

    <!-- Header + Search -->
    <section class="mb-6 flex flex-col sm:flex-row sm:items-end gap-3">
      <div class="flex-1">
        <h1 class="text-2xl font-bold">Produk</h1>
        <p class="text-sm text-slate-500 mt-1">
          Pilih produk lalu lanjutkan ke checkout.
        </p>
      </div>

      <form method="get" class="flex gap-2 w-full sm:w-auto">
        <input
          name="q"
          :value="filters?.q ?? ''"
          placeholder="Cari nama / SKU"
          class="w-full sm:w-64 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
        />
        <button
          type="submit"
          class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800"
        >
          Cari
        </button>
      </form>
    </section>

    <!-- Empty state -->
    <div v-if="!products?.data?.length" class="py-16 text-center">
      <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 grid place-items-center">
        <span class="text-slate-400 text-xl">🛍️</span>
      </div>
      <p class="mt-4 font-medium">Produk tidak ditemukan</p>
      <p class="text-sm text-slate-500 mt-1">
        Coba ubah kata kunci atau hapus filter pencarian.
      </p>
      <div class="mt-6">
        <Link
          href="/"
          class="inline-flex rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
        >
          Reset Pencarian
        </Link>
      </div>
    </div>

    <!-- Grid Produk -->
    <ul
      v-else
      class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
    >
      <li
        v-for="p in products.data"
        :key="p.id"
        class="group rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition"
      >
        <!-- placeholder gambar -->
        <div class="aspect-[4/3] bg-slate-100 grid place-items-center">
          <span class="text-slate-400 text-sm">Image</span>
        </div>

        <div class="p-4">
          <div class="flex items-start justify-between gap-3">
            <h3 class="font-semibold leading-tight group-hover:underline">
              {{ p.name }}
            </h3>
            <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-700">
              SKU: {{ p.sku }}
            </span>
          </div>

          <p class="mt-2 text-sm text-slate-500 line-clamp-2">
            {{ p.description ?? '—' }}
          </p>

          <div class="mt-4 flex items-center justify-between">
            <p class="font-bold">Rp {{ formatIDR(p.price) }}</p>
            <Link
              :href="`/checkout/start?product_id=${p.id}`"
              as="button"
              class="inline-flex items-center rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800"
            >
              Beli
            </Link>
          </div>
        </div>
      </li>
    </ul>

    <!-- Pagination -->
    <nav class="mt-6 flex flex-wrap items-center gap-2">
      <Link
        v-for="l in products.links"
        :key="l.url ?? l.label"
        :href="l.url || '#'"
        v-html="l.label"
        :class="[
          'px-3 py-1.5 rounded-lg border text-sm',
          l.active
            ? 'border-slate-800 bg-slate-900 text-white'
            : 'border-slate-200 bg-white hover:bg-slate-50',
          !l.url && 'opacity-50 pointer-events-none'
        ]"
      />
    </nav>
  </AppLayout>
</template>
