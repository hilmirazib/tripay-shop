<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  message: { type: String, required: true },

  status: { type: String, default: 'PENDING' },        
  reference: { type: String, default: '' },            
  amount: { type: Number, default: null },             
  continue_url: { type: String, default: '' },         
})
const formatIDR = (n) =>
  n == null ? '—' : n.toLocaleString('id-ID', { maximumFractionDigits: 0 })
</script>

<template>
  <AppLayout>
    <Head title="Kembali dari Pembayaran" />

    <main class="max-w-xl mx-auto">
      <div class="text-center mb-6">
        <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 grid place-items-center">
          <span class="text-2xl">🎉</span>
        </div>
        <h1 class="text-2xl font-bold mt-4">Terima kasih!</h1>
        <p class="text-slate-700 mt-2">{{ message }}</p>
        <p class="mt-3 text-sm text-slate-500">
          Status transaksi akan otomatis diperbarui setelah callback dari TriPay diterima.
        </p>
      </div>

      <!-- Kartu ringkasan -->
      <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="space-y-1 text-sm">
            <div class="text-slate-500">Reference</div>
            <div class="font-mono text-slate-800">{{ reference || '—' }}</div>
          </div>

          <!-- badge status -->
          <span
            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
            :class="{
              'bg-green-100 text-green-700': status === 'PAID',
              'bg-yellow-100 text-yellow-700': status === 'PENDING' || status === 'UNPAID',
              'bg-red-100 text-red-700': status === 'EXPIRED' || status === 'FAILED',
              'bg-slate-100 text-slate-700': !status
            }"
          >
            {{ status || 'PENDING' }}
          </span>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
          <div class="rounded-xl bg-slate-50 p-3">
            <div class="text-slate-500">Total</div>
            <div class="font-semibold">Rp {{ formatIDR(amount) }}</div>
          </div>
          <div class="rounded-xl bg-slate-50 p-3">
            <div class="text-slate-500">Metode</div>
            <div class="font-semibold">
              <!-- kalau mau, kirim 'method_name' dari server -->
              <slot name="method">—</slot>
            </div>
          </div>
        </div>

        <!-- actions -->
        <div class="mt-6 flex flex-wrap items-center gap-3">
          <Link
            href="/my-invoices"
            class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
          >
            Lihat Invoice Saya
          </Link>

          <Link
            v-if="continue_url"
            :href="continue_url"
            class="inline-flex items-center rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800"
          >
            Lanjutkan Pembayaran
          </Link>

          <Link
            href="/"
            class="inline-flex items-center rounded-xl px-4 py-2 text-sm text-slate-600 hover:text-slate-900"
          >
            Kembali ke Produk
          </Link>
        </div>

        <p class="mt-4 text-xs text-slate-500">
          Jika status belum berubah, silakan buka halaman invoice untuk melihat pembaruan terbaru.
        </p>
      </section>
    </main>
  </AppLayout>
</template>
