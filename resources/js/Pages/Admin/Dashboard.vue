<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  stats: { type: Array, required: true }
})
</script>

<template>
  <AppLayout>
    <Head title="Admin Dashboard" />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Judul -->
      <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

      <!-- Statistik ringkas -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div
          v-for="s in props.stats"
          :key="s.label"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition"
        >
          <Link :href="s.href" class="block">
            <p class="text-sm text-slate-500">{{ s.label }}</p>
            <p class="mt-1 text-2xl font-semibold text-slate-800">{{ s.value }}</p>
          </Link>
        </div>
      </div>

      <!-- Navigasi cepat -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <Link
          href="/admin/products"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col"
        >
          <h2 class="font-semibold text-lg text-slate-800">Kelola Produk</h2>
          <p class="text-sm text-slate-500 mt-1">
            Tambah, edit, atau hapus produk yang tersedia untuk dijual.
          </p>
        </Link>

        <Link
          href="/admin/invoices"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col"
        >
          <h2 class="font-semibold text-lg text-slate-800">Kelola Invoices</h2>
          <p class="text-sm text-slate-500 mt-1">
            Lihat semua transaksi pelanggan dan periksa status pembayaran.
          </p>
        </Link>

        <Link
          href="/"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col"
        >
          <h2 class="font-semibold text-lg text-slate-800">Kembali ke Toko</h2>
          <p class="text-sm text-slate-500 mt-1">
            Buka halaman depan toko untuk melihat tampilan customer.
          </p>
        </Link>
      </div>
    </main>
  </AppLayout>
</template>
