<script setup>
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  invoices: {
    type: Object,
    required: true,
    default: () => ({ data: [], links: [] })
  }
})
</script>

<template>
  <Head title="My Invoices" />
  <main class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">My Invoices</h1>

    <ul class="grid gap-4">
      <li
        v-for="inv in invoices.data"
        :key="inv.id"
        class="border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition"
      >
        <div class="font-semibold text-gray-800">
          {{ inv.product?.name }}
          <span class="ml-2 text-sm font-normal text-gray-600">— Rp {{ inv.amount.toLocaleString('id-ID') }}</span>
        </div>
        <div class="text-sm text-gray-500">Ref: {{ inv.tripay_reference ?? inv.merchant_ref }}</div>
        <div class="text-sm">
          Status:
          <span
            class="px-2 py-0.5 rounded-full text-xs font-medium"
            :class="{
              'bg-green-100 text-green-800': inv.status === 'PAID',
              'bg-yellow-100 text-yellow-800': inv.status === 'PENDING',
              'bg-red-100 text-red-800': inv.status === 'FAILED'
            }"
          >
            {{ inv.status }}
          </span>
        </div>
        <div class="text-sm text-gray-600">
          Date: {{ new Date(inv.created_at).toLocaleString() }}
        </div>
      </li>
    </ul>
  </main>
</template>
