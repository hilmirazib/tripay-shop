<script setup>
import { Head, Link, router } from '@inertiajs/vue3'

const props = defineProps({
  filters: Object,
  products: Object
})

function search(e) {
  const form = e.target
  const params = new URLSearchParams(new FormData(form))
  router.get('/admin/products', params, { preserveState: true, preserveScroll: true })
}
</script>

<template>
  <Head title="Products" />
  <main class="px-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <!-- Search + New -->
    <div class="flex items-center gap-3 mb-4">
      <form @submit.prevent="search" class="flex gap-2">
        <input
          name="q"
          :value="filters?.q ?? ''"
          placeholder="Search by name / SKU"
          class="border rounded px-3 py-2"
        />
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          Search
        </button>
      </form>
      <Link
        href="/admin/products/create"
        as="button"
        class="bg-green-600 text-white px-4 py-2 rounded"
      >
        + New
      </Link>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto border rounded">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">SKU</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Price</th>
            <th class="px-4 py-2 border">Reference</th>
            <th class="px-4 py-2 border w-32"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products.data" :key="p.id" class="hover:bg-gray-50">
            <td class="px-4 py-2 border">{{ p.id }}</td>
            <td class="px-4 py-2 border">{{ p.sku }}</td>
            <td class="px-4 py-2 border">{{ p.name }}</td>
            <td class="px-4 py-2 border">Rp {{ (p.price).toLocaleString('id-ID') }}</td>
            <td class="px-4 py-2 border">{{ p.reference ?? '-' }}</td>
            <td class="px-4 py-2 border whitespace-nowrap space-x-2">
              <Link
                :href="`/admin/products/${p.id}/edit`"
                as="button"
                class="bg-yellow-500 text-white px-3 py-1 rounded"
              >
                Edit
              </Link>
              <Link
                :href="`/admin/products/${p.id}`"
                method="delete"
                as="button"
                class="bg-red-600 text-white px-3 py-1 rounded"
                onclick="return confirm('Delete?')"
              >
                Delete
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
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
