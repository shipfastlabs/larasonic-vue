<script setup>
import AuthenticationCardLogo from '@/components/LogoRedirect.vue'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags'

defineProps({
  terms: String,
})

useSeoMetaTags({
  title: 'Terms of Service',
})
</script>

<template>
  <div class="font-sans antialiased">
    <div class="pt-4">
      <div class="flex min-h-screen flex-col items-center pt-6 sm:pt-0">
        <div>
          <AuthenticationCardLogo />
        </div>

        <div class="prose dark:prose-invert mt-6 w-full overflow-hidden p-6 shadow-md sm:max-w-2xl sm:rounded-lg" v-html="terms" />
      </div>
    </div>
  </div>
</template>
