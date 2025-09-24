<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  message: {
    type: String,
    required: true
  }
})
</script>

<template>
  <Head title="Kembali dari Pembayaran" />

  <main class="max-w-2xl mx-auto p-6 text-center">
    <h1 class="text-2xl font-bold mb-3">Terima kasih!</h1>
    <p class="text-gray-700">{{ message }}</p>

    <p class="mt-5 text-gray-600">
      Status transaksi Anda akan otomatis diperbarui setelah kami menerima callback dari TriPay.
    </p>

    <Link
      href="/my-invoices"
      as="button"
      class="mt-6 inline-block px-4 py-2 border border-gray-700 rounded-md text-gray-700 hover:bg-gray-100 transition"
    >
      Lihat Invoice Saya
    </Link>
  </main>
</template>
