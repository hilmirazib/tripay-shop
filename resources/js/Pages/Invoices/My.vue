<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  invoices: {
    type: Object,
    required: true,
    default: () => ({ data: [], links: [] })
  }
})

const formatIDR = (n) =>
  (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })
</script>

<template>
  <AppLayout>
    <Head title="My Invoices" />

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-bold mb-6">My Invoices</h1>

      <div
        v-if="!invoices?.data?.length"
        class="text-center py-20 border border-dashed border-slate-300 rounded-2xl bg-white"
      >
        <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 grid place-items-center">
          <span class="text-2xl">📄</span>
        </div>
        <p class="mt-4 font-medium">Belum ada invoice</p>
        <p class="text-sm text-slate-500 mt-1">
          Anda belum melakukan transaksi apapun.
        </p>
        <div class="mt-6">
          <Link
            href="/"
            class="inline-flex rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
          >
            Belanja Produk
          </Link>
        </div>
      </div>

      <!-- List invoice -->
      <ul v-else class="grid gap-4">
        <li
          v-for="inv in invoices.data"
          :key="inv.id"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition"
        >
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-slate-800 truncate">
                {{ inv.product?.name ?? '—' }}
              </div>
              <div class="text-xs text-slate-500 mt-0.5">
                Ref: {{ inv.tripay_reference ?? inv.merchant_ref }}
              </div>
              <div class="text-sm text-slate-600 mt-1">
                {{ new Date(inv.created_at).toLocaleString() }}
              </div>
            </div>

            <!-- Status badge -->
            <span
              class="shrink-0 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
              :class="{
                'bg-green-100 text-green-700': inv.status === 'PAID',
                'bg-yellow-100 text-yellow-700': inv.status === 'UNPAID' || inv.status === 'PENDING' || inv.status === 'pending_local',
                'bg-red-100 text-red-700': inv.status === 'FAILED' || inv.status === 'EXPIRED',
                'bg-slate-100 text-slate-700': !inv.status
              }"
            >
              {{ inv.status ?? 'PENDING' }}
            </span>
          </div>

          <div class="mt-3 flex items-center justify-between">
            <p class="font-semibold">
              Rp {{ formatIDR(inv.amount) }}
            </p>
            <div class="flex gap-2">
              <Link
                v-if="inv.checkout_url && inv.status !== 'PAID'"
                :href="inv.checkout_url"
                class="inline-flex items-center rounded-xl bg-slate-900 text-white px-3 py-1.5 text-sm hover:bg-slate-800"
              >
                Bayar
              </Link>
              <!-- <Link
                :href="`/invoices/${inv.id}`"
                class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-100"
              >
                Detail
              </Link> -->
            </div>
          </div>
        </li>
      </ul>

      <nav
        v-if="invoices.links?.length > 3"
        class="mt-6 flex flex-wrap items-center gap-2"
      >
        <Link
          v-for="l in invoices.links"
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
    </main>
  </AppLayout>
</template>
