<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'

const page = usePage()
const auth = computed(() => page.props.auth)

const isAdmin = computed(() => {
  const roles = auth.value?.user?.roles ?? []
  return roles.includes('admin')
})
</script>

<template>
  <nav style="display:flex;gap:16px;padding:12px;border-bottom:1px solid #eee">
    <Link href="/">Home</Link>

    <template v-if="auth?.user">
      <!-- hanya admin -->
      <Link v-if="isAdmin" href="/admin">Admin</Link>

      <span style="margin-left:auto">Hi, {{ auth.user.name }}</span>
      <Link href="/logout" method="post" as="button">Logout</Link>
    </template>
    <template v-else>
      <Link href="/login">Login</Link>
      <Link href="/register">Register</Link>
    </template>
  </nav>
</template>
