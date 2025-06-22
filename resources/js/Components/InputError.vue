<script setup>
import Label from '@/components/ui/label/Label.vue'
import { cn } from '@/lib/utils'

defineProps({
  message: String,
})
</script>

<template>
  <Label v-show="message" :class="cn(message && 'text-destructive')">
    {{ message }}
  </Label>
</template>
