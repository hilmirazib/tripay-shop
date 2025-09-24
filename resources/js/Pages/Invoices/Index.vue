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
  <Head title="All Invoices" />
  <main class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">All Invoices</h1>

    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">ID</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Product</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Reference</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Buyer</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Amount</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Date</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr
            v-for="inv in invoices.data"
            :key="inv.id"
            class="hover:bg-gray-50"
          >
            <td class="px-4 py-2 text-sm text-gray-800">{{ inv.id }}</td>
            <td class="px-4 py-2 text-sm text-gray-800">{{ inv.product?.name }}</td>
            <td class="px-4 py-2 text-sm text-gray-800">{{ inv.tripay_reference ?? inv.merchant_ref }}</td>
            <td class="px-4 py-2 text-sm text-gray-800">{{ inv.buyer_email }}</td>
            <td class="px-4 py-2 text-sm text-gray-800">Rp {{ inv.amount.toLocaleString('id-ID') }}</td>
            <td class="px-4 py-2 text-sm">
              <span
                class="px-2 py-1 rounded-full text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-800': inv.status === 'PAID',
                  'bg-yellow-100 text-yellow-800': inv.status === 'PENDING',
                  'bg-red-100 text-red-800': inv.status === 'FAILED'
                }"
              >
                {{ inv.status }}
              </span>
            </td>
            <td class="px-4 py-2 text-sm text-gray-800">
              {{ new Date(inv.created_at).toLocaleString() }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

