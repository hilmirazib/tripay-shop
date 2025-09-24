<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { z } from 'zod'

const props = defineProps({
  product: {
    type: Object,
    required: true,
    validator: (p) =>
      p && typeof p.id === 'number' &&
      typeof p.name === 'string' &&
      typeof p.sku === 'string' &&
      typeof p.price === 'number'
  },
  methods: { type: Array, default: () => [] } // [{ code, name, feeFix?, feePercent? }]
})

// zod schema sama seperti punyamu
const schema = z.object({
  product_id: z.number().int().positive(),
  buyer_email: z.string().email(),
  buyer_phone: z.string().min(6),
  payment_method: z.string().min(1),
})

const form = useForm({
  product_id: props.product.id,
  buyer_email: '',
  buyer_phone: '',
  payment_method: '',
})

const formatIDR = (n) => (n ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 0 })

function submit() {
  const parsed = schema.safeParse(form.data())
  if (!parsed.success) {
    const err = {}
    parsed.error.issues.forEach(i => {
      const k = i.path?.[0] ?? 'form'
      err[k] = i.message
    })
    form.setError(err)
    return
  }
  form.clearErrors()
  form.post('/checkout')
}
</script>

<template>
  <AppLayout>
    <Head :title="`Checkout ${product.name}`" />

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-2xl font-bold mb-4">Checkout</h1>

      <div class="grid lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form -->
        <section class="lg:col-span-2 space-y-6">
          <!-- Product card -->
          <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-start justify-between gap-4">
              <div>
                <div class="font-semibold text-lg leading-tight">{{ product.name }}</div>
                <div class="text-xs text-slate-500 mt-0.5">SKU: {{ product.sku }}</div>
              </div>
              <div class="text-right">
                <div class="text-sm text-slate-500">Harga</div>
                <div class="text-lg font-bold">Rp {{ formatIDR(product.price) }}</div>
              </div>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Metode Pembayaran (tiles) -->
            <fieldset class="rounded-2xl border border-slate-200 bg-white p-5">
              <legend class="block font-semibold">Metode Pembayaran</legend>
              <p class="text-sm text-slate-500 mt-1">Pilih salah satu metode di bawah.</p>

              <div class="mt-4 grid sm:grid-cols-2 gap-3">
                <div v-for="(m, idx) in methods" :key="m.code ?? idx">
                  <input
                    :id="`pay-${idx}`"
                    type="radio"
                    name="payment_method"
                    :value="String(m.code)"
                    v-model="form.payment_method"
                    class="peer sr-only"
                  />
                  <label
                    :for="`pay-${idx}`"
                    class="peer-checked:border-slate-900 peer-checked:ring-2 peer-checked:ring-slate-200
                          flex items-center gap-3 rounded-xl border border-slate-200 p-4 cursor-pointer hover:bg-slate-50"
                  >
                    <div class="grid place-items-center w-10 h-10 rounded-lg bg-slate-100 text-slate-500 text-sm">
                      {{ String(m.code || 'PAY')[0] }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-medium truncate">{{ m.name }}</p>
                      <p class="text-xs text-slate-500">Code: {{ m.code }}</p>
                    </div>
                  </label>
                </div>
              </div>

              <p v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600">
                {{ form.errors.payment_method }}
              </p>
            </fieldset>
            <!-- Kontak -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 grid sm:grid-cols-2 gap-4">
              <div>
                <label for="buyer_email" class="text-sm font-medium">Email</label>
                <input
                  id="buyer_email"
                  v-model="form.buyer_email"
                  type="email"
                  placeholder="you@example.com"
                  autocomplete="email"
                  class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm
                         focus:outline-none focus:ring-2 focus:ring-slate-300"
                />
                <p v-if="form.errors.buyer_email" class="mt-1 text-sm text-red-600">{{ form.errors.buyer_email }}</p>
              </div>

              <div>
                <label for="buyer_phone" class="text-sm font-medium">No. HP</label>
                <input
                  id="buyer_phone"
                  v-model="form.buyer_phone"
                  type="tel"
                  inputmode="numeric"
                  autocomplete="tel"
                  placeholder="08xxxxxxxxxx"
                  class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm
                         focus:outline-none focus:ring-2 focus:ring-slate-300"
                />
                <p v-if="form.errors.buyer_phone" class="mt-1 text-sm text-red-600">{{ form.errors.buyer_phone }}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center rounded-xl bg-slate-900 text-white px-4 py-2 text-sm
                       hover:bg-slate-800 disabled:opacity-50"
              >
                {{ form.processing ? 'Memproses…' : 'Lanjutkan' }}
              </button>
              <Link
                href="/"
                as="button"
                class="inline-flex items-center rounded-xl border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100"
              >
                Batal
              </Link>
            </div>

            <!-- Error umum -->
            <div v-if="Object.keys(form.errors).length" class="text-red-600 text-sm">
              <div v-for="(msg, key) in form.errors" :key="key">
                {{ key }}: {{ msg }}
              </div>
            </div>
          </form>
        </section>

        <!-- Kolom Kanan: Ringkasan -->
        <aside class="lg:col-span-1">
          <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <h3 class="font-semibold">Ringkasan Pembelian</h3>
            <div class="mt-4 space-y-2 text-sm">
              <div class="flex justify-between">
                <span>Produk</span>
                <span class="font-medium text-right line-clamp-1">{{ product.name }}</span>
              </div>
              <div class="flex justify-between">
                <span>Harga</span>
                <span class="font-semibold">Rp {{ formatIDR(product.price) }}</span>
              </div>
              <div class="flex justify-between text-slate-500">
                <span>Biaya Admin</span>
                <span>Ditambahkan di gateway</span>
              </div>
              <hr class="my-3">
              <div class="flex justify-between">
                <span>Total</span>
                <span class="font-bold">Rp {{ formatIDR(product.price) }}</span>
              </div>
            </div>
            <p class="text-xs text-slate-500 mt-4">
              Setelah melanjutkan, kamu akan diarahkan / ditampilkan instruksi pembayaran.
            </p>
          </div>
        </aside>
      </div>
    </div>
  </AppLayout>
</template>
