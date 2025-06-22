<script setup>
import { Icon } from '@iconify/vue'
import { useChangeCase } from '@vueuse/integrations/useChangeCase'
import { Button } from '@/components/ui/button'

defineProps({
  provider: {
    type: Object,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <Button
    :disabled="disabled"
    class="bg-background text-foreground hover:bg-secondary disabled:opacity-50 dark:hover:bg-primary/80 dark:bg-primary dark:text-primary-foreground"
    as="a" :href="route('oauth.redirect', { provider: provider.slug })"
  >
    <Icon :icon="provider.icon" class="mr-2 h-4 w-4" />
    Sign In With {{ useChangeCase(provider.slug, 'sentenceCase') }}
  </Button>
</template>
