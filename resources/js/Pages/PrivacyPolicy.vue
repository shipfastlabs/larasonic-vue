<script setup>
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
  policy: String,
})
</script>

<template>
  <Head title="Privacy Policy" />

  <div class="font-sans antialiased">
    <div class="pt-4">
      <div class="flex min-h-screen flex-col items-center pt-6 sm:pt-0">
        <div>
          <AuthenticationCardLogo />
        </div>

        <div class="prose mt-6 w-full overflow-hidden bg-white p-6 shadow-md dark:prose-invert dark:bg-gray-800 sm:max-w-2xl sm:rounded-lg" v-html="policy" />
      </div>
    </div>
  </div>
</template>
