<script setup>
import { computed, watch, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const success = computed(() => (page.props.flash)?.success)
const error   = computed(() => (page.props.flash)?.error)

// (opsional) auto-hide
const showSuccess = ref(false)
const showError = ref(false)

watch(success, (v) => {
  if (v) {
    showSuccess.value = true
    setTimeout(() => showSuccess.value = false, 3000)
  }
})
watch(error, (v) => {
  if (v) {
    showError.value = true
    setTimeout(() => showError.value = false, 3000)
  }
})
</script>

<template>
  <div class="fixed right-4 bottom-4 grid gap-2 z-50">
  <div
    v-if="success"
    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-lg min-w-[240px] shadow"
  >
    {{ success }}
  </div>

  <div
    v-if="error"
    class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-2 rounded-lg min-w-[240px] shadow"
  >
    {{ error }}
  </div>
</div>

</template>
