<script setup>
const props = defineProps({
  product: { type: Object, required: true }
})
</script>

<template>
  <article class="group rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition">
    <div class="aspect-[4/3] bg-slate-100 grid place-items-center">
      <span class="text-slate-400 text-sm">Image</span>
    </div>
    <div class="p-4">
      <h3 class="font-semibold group-hover:underline">{{ product.name }}</h3>
      <p class="text-slate-500 text-sm mt-1">{{ product.sku }}</p>
      <p class="mt-3 font-semibold">Rp {{ (product.price ?? 0).toLocaleString('id-ID') }}</p>
      <div class="mt-4 flex items-center gap-2">
        <Link :href="`/checkout/${product.id}`"
              class="inline-flex justify-center items-center rounded-xl bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800">
          Beli
        </Link>
        <button type="button"
                class="inline-flex justify-center items-center rounded-xl border px-3 py-2 text-sm border-slate-300 hover:bg-slate-100">
          Detail
        </button>
      </div>
    </div>
  </article>
</template>
