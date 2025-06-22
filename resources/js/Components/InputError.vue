<script setup>
import { cn } from '@/lib/utils'
import Label from '@/components/ui/label/Label.vue'

defineProps({
  message: String,
})
</script>

<template>
  <Label v-show="message" :class="cn(message && 'text-destructive')">
    {{ message }}
  </Label>
</template>
