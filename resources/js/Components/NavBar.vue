<script setup>
import { ref, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

const page = usePage()
const auth = computed(() => page.props.auth)

const isAdmin = computed(() => {
  const roles = auth.value?.user?.roles ?? []
  return roles.includes('admin')
})

// untuk mobile toggle
const open = ref(false)
</script>

<template>
  <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <!-- kiri -->
      <div class="flex items-center gap-6">
        <Link href="/" class="font-bold text-lg tracking-tight text-slate-900">
          MiniShop
        </Link>

        <div class="hidden md:flex items-center gap-4 text-sm">
          <Link href="/" class="hover:text-slate-900 text-slate-600">Home</Link>
          <Link v-if="isAdmin" href="/admin" class="hover:text-slate-900 text-slate-600">Admin</Link>
        </div>
      </div>

      <!-- kanan -->
      <div class="hidden md:flex items-center gap-4">
        <template v-if="auth?.user">
          <span class="text-sm text-slate-700">Hi, {{ auth.user.name }}</span>
          <Link href="/logout" method="post" as="button"
                class="rounded-xl border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-100">
            Logout
          </Link>
        </template>
        <template v-else>
          <Link href="/login" class="text-sm text-slate-600 hover:text-slate-900">Login</Link>
          <Link href="/register" class="rounded-xl bg-slate-900 text-white px-3 py-1.5 text-sm hover:bg-slate-800">
            Register
          </Link>
        </template>
      </div>

      <!-- hamburger -->
      <button @click="open = !open" class="md:hidden p-2 rounded hover:bg-slate-100">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </nav>

    <!-- mobile menu -->
    <div v-if="open" class="md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-2">
      <Link href="/" class="block text-slate-600 hover:text-slate-900">Home</Link>
      <Link v-if="isAdmin" href="/admin" class="block text-slate-600 hover:text-slate-900">Admin</Link>

      <template v-if="auth?.user">
        <span class="block text-slate-700">Hi, {{ auth.user.name }}</span>
        <Link href="/logout" method="post" as="button"
              class="block rounded-xl border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-100">
          Logout
        </Link>
      </template>
      <template v-else>
        <Link href="/login" class="block text-slate-600 hover:text-slate-900">Login</Link>
        <Link href="/register"
              class="block rounded-xl bg-slate-900 text-white px-3 py-1.5 text-sm hover:bg-slate-800">
          Register
        </Link>
      </template>
    </div>
  </header>
</template>
