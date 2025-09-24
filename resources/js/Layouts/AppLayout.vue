<script setup>
import NavBar from '@/Components/NavBar.vue';
</script>

<template>
  <div class="min-h-dvh bg-slate-50 text-slate-800">
    <!-- Top Nav -->
    <NavBar />

    <!-- Main -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 py-6 mt-12 text-center text-sm text-slate-500">
      © {{ new Date().getFullYear() }} MiniShop · Built with Laravel · Vue · Inertia · Tailwind
    </footer>
  </div>
</template>
