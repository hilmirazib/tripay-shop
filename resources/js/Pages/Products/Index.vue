<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

import { useAbility } from '@/composables/useAbility'

const { can } = useAbility()

const props = defineProps({
  filters: { type: Object, default: () => ({ q: '' }) },
  products: { type: Object, required: true }
})

const q = ref(props.filters?.q ?? '')

function search(e) {
  const form = e?.target
  if (form) {
    const params = new URLSearchParams(new FormData(form))
    router.get('/admin/products', params, { preserveState: true, preserveScroll: true })
  } else {
    router.get('/admin/products', { q: q.value }, { preserveState: true, preserveScroll: true })
  }
}

function resetFilters() {
  q.value = ''
  router.get('/admin/products', {}, { preserveState: true, preserveScroll: true })
}

const formatIDR = (n) => (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })
</script>

<template>
  <AppLayout>
    <Head title="Products" />

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold">Products</h1>
        <Link
          v-if="can('product.create')"
          href="/admin/products/create"
          as="button"
          class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800"
        >
          + New
        </Link>
      </div>

      <!-- Search -->
      <form @submit.prevent="search" class="rounded-2xl border border-slate-200 bg-white p-4 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-end gap-3">
          <div class="flex-1">
            <label class="text-sm font-medium">Search</label>
            <input
              name="q"
              v-model="q"
              placeholder="Search by name / SKU"
              class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
            />
          </div>
          <div class="flex items-center gap-2">
            <button
              type="submit"
              class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800"
            >
              Search
            </button>
            <button
              type="button"
              @click="resetFilters"
              class="rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
            >
              Reset
            </button>
          </div>
        </div>
      </form>

      <!-- Empty state -->
      <div
        v-if="!products?.data?.length"
        class="text-center py-20 border border-dashed border-slate-300 rounded-2xl bg-white"
      >
        <div class="mx-auto w-14 h-14 rounded-full bg-slate-100 grid place-items-center">
          <span class="text-2xl">📦</span>
        </div>
        <p class="mt-4 font-medium">No products found</p>
        <p class="text-sm text-slate-500 mt-1">
          Try a different keyword or reset your search.
        </p>
        <div class="mt-6">
          <button
            type="button"
            @click="resetFilters"
            class="inline-flex rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
          >
            Reset Search
          </button>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="min-w-[900px] w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 sticky top-0 z-10">
            <tr>
              <th class="text-left px-4 py-3">ID</th>
              <th class="text-left px-4 py-3">SKU</th>
              <th class="text-left px-4 py-3">Name</th>
              <th class="text-right px-4 py-3">Price</th>
              <th class="text-left px-4 py-3">Reference</th>
              <th class="text-right px-4 py-3 w-40">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="p in products.data"
              :key="p.id"
              class="border-t border-slate-100 hover:bg-slate-50"
            >
              <td class="px-4 py-3 font-mono text-xs">{{ p.id }}</td>
              <td class="px-4 py-3">{{ p.sku }}</td>
              <td class="px-4 py-3">
                <div class="font-medium text-slate-800 truncate max-w-[360px]">
                  {{ p.name }}
                </div>
              </td>
              <td class="px-4 py-3 text-right font-semibold">
                Rp {{ formatIDR(p.price) }}
              </td>
              <td class="px-4 py-3">
                <span class="text-xs rounded-full bg-slate-100 text-slate-700 px-2 py-0.5">
                  {{ p.reference ?? '—' }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="inline-flex items-center gap-2">
                  <Link
                    :href="`/admin/products/${p.id}/edit`"
                    v-if="can('product.update')"
                    as="button"
                    class="rounded-xl border border-slate-300 px-3 py-1.5 text-xs hover:bg-slate-100"
                  >
                    Edit
                  </Link>
                  <Link
                    :href="`/admin/products/${p.id}`"
                    v-if="can('product.delete')"
                    method="delete"
                    as="button"
                    class="rounded-xl bg-red-600 text-white px-3 py-1.5 text-xs hover:bg-red-700"
                    @click.prevent="(e) => { if (confirm('Delete this product?')) e.target.closest('form')?.submit?.() }"
                  >
                    Delete
                  </Link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav v-if="products.links?.length > 3" class="mt-6 flex flex-wrap items-center gap-2">
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
    </main>
  </AppLayout>
</template>
