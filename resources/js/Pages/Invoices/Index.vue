<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'

const props = defineProps({
  invoices: {
    type: Object,
    required: true, // { data, links, ... }
    default: () => ({ data: [], links: [] })
  }
})

const page = usePage()

// --- Helpers
const formatIDR = (n) => (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })
const statusClass = (s) => {
  if (s === 'PAID') return 'bg-green-100 text-green-700'
  if (s === 'FAILED' || s === 'EXPIRED') return 'bg-red-100 text-red-700'
  return 'bg-yellow-100 text-yellow-700' // UNPAID/PENDING/pending_local/other
}

// Parse query dari URL Inertia saat ini -> prefill form
function getQuery() {
  try {
    const url = new URL(page.url, window.location.origin)
    const q = (url.searchParams.get('q') ?? '').trim()
    const status = (url.searchParams.get('status') ?? '').trim()
    const method = (url.searchParams.get('method') ?? '').trim()
    const from = url.searchParams.get('from') ?? ''
    const to = url.searchParams.get('to') ?? ''
    return { q, status, method, from, to }
  } catch {
    return { q: '', status: '', method: '', from: '', to: '' }
  }
}

const form = ref(getQuery())

// Methods dropdown: distinct dari invoices.data
const methodsOptions = computed(() => {
  const set = new Set()
  for (const inv of props.invoices?.data ?? []) {
    if (inv?.payment_method) set.add(String(inv.payment_method))
  }
  return Array.from(set).sort().map(code => ({ code, name: code }))
})

function submitFilters() {
  router.get('/admin/invoices', { ...form.value }, { preserveScroll: true, preserveState: true })
}

function resetFilters() {
  form.value = { q: '', status: '', method: '', from: '', to: '' }
  submitFilters()
}

async function copy(text) { try { await navigator.clipboard.writeText(text) } catch {} }
</script>

<template>
  <AppLayout>
    <Head title="All Invoices" />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">All Invoices</h1>
        <Link href="/" class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-100">
          Ke Produk
        </Link>
      </div>

      <!-- Filters (no server props needed) -->
      <form @submit.prevent="submitFilters" class="rounded-2xl border border-slate-200 bg-white p-4 mb-4">
        <div class="grid md:grid-cols-5 gap-3">
          <div class="md:col-span-2">
            <label class="text-sm font-medium">Cari</label>
            <input v-model="form.q" type="search" placeholder="Nama/Ref/Email/HP/SKU"
                   class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"/>
          </div>

          <div>
            <label class="text-sm font-medium">Status</label>
            <select v-model="form.status" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
              <option value="">Semua</option>
              <option value="PAID">PAID</option>
              <option value="UNPAID">UNPAID</option>
              <option value="PENDING">PENDING</option>
              <option value="FAILED">FAILED</option>
              <option value="EXPIRED">EXPIRED</option>
              <option value="pending_local">pending_local</option>
            </select>
          </div>

          <div>
            <label class="text-sm font-medium">Metode</label>
            <select v-model="form.method" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
              <option value="">Semua</option>
              <option v-for="m in methodsOptions" :key="m.code" :value="m.code">{{ m.name }}</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-sm font-medium">Dari</label>
              <input v-model="form.from" type="date" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"/>
            </div>
            <div>
              <label class="text-sm font-medium">Sampai</label>
              <input v-model="form.to" type="date" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"/>
            </div>
          </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-2">
          <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800">
            Terapkan
          </button>
          <button type="button" @click="resetFilters" class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100">
            Reset
          </button>
        </div>
      </form>

      <!-- Empty -->
      <div v-if="!invoices?.data?.length" class="text-center py-20 border border-dashed border-slate-300 rounded-2xl bg-white">
        <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 grid place-items-center">
          <span class="text-2xl">🧾</span>
        </div>
        <p class="mt-4 font-medium">Tidak ada invoice</p>
        <p class="text-sm text-slate-500 mt-1">Ubah filter di atas untuk menampilkan data.</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-[1000px] w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 sticky top-0 z-10">
            <tr>
              <th class="text-left px-4 py-3">ID</th>
              <th class="text-left px-4 py-3">Product</th>
              <th class="text-left px-4 py-3">Reference</th>
              <th class="text-left px-4 py-3">Method</th>
              <th class="text-left px-4 py-3">Buyer</th>
              <th class="text-right px-4 py-3">Amount</th>
              <th class="text-left px-4 py-3">Status</th>
              <th class="text-right px-4 py-3">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="inv in invoices.data" :key="inv.id" class="border-t border-slate-100 hover:bg-slate-50">
              <td class="px-4 py-3 font-mono text-xs">{{ inv.id }}</td>

              <td class="px-4 py-3">
                <div class="font-medium truncate max-w-[260px]">{{ inv.product?.name ?? '—' }}</div>
                <div class="text-xs text-slate-500">SKU: {{ inv.product?.sku ?? '—' }}</div>
              </td>

              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <span class="font-mono text-xs">{{ inv.tripay_reference ?? inv.merchant_ref }}</span>
                  <button type="button" @click="copy(inv.tripay_reference ?? inv.merchant_ref)"
                          class="rounded border border-slate-300 px-2 py-0.5 text-xs hover:bg-slate-100" title="Copy reference">
                    Salin
                  </button>
                </div>
                <div v-if="inv.merchant_ref && inv.tripay_reference" class="text-[10px] text-slate-500">
                  mref: {{ inv.merchant_ref }}
                </div>
              </td>

              <td class="px-4 py-3">
                <span class="text-xs rounded-full bg-slate-100 text-slate-700 px-2 py-0.5">
                  {{ inv.payment_method ?? '—' }}
                </span>
              </td>

              <td class="px-4 py-3">
                <div class="truncate max-w-[220px]">{{ inv.buyer_email ?? '—' }}</div>
                <div class="text-xs text-slate-500">{{ inv.buyer_phone ?? '—' }}</div>
              </td>

              <td class="px-4 py-3 text-right font-semibold">Rp {{ formatIDR(inv.amount) }}</td>

              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium" :class="statusClass(inv.status)">
                  {{ inv.status ?? 'PENDING' }}
                </span>
              </td>

              <td class="px-4 py-3 text-right text-slate-600">
                {{ new Date(inv.created_at).toLocaleString() }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav v-if="invoices.links?.length > 3" class="mt-6 flex flex-wrap items-center gap-2">
        <Link v-for="l in invoices.links" :key="l.url ?? l.label" :href="l.url || '#'" v-html="l.label"
              :class="[
                'px-3 py-1.5 rounded-lg border text-sm',
                l.active ? 'border-slate-800 bg-slate-900 text-white'
                         : 'border-slate-200 bg-white hover:bg-slate-50',
                !l.url && 'opacity-50 pointer-events-none'
              ]"/>
      </nav>
    </main>
  </AppLayout>
</template>
