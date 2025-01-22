<script setup>
import { cn } from '@/lib/utils'
import { X } from 'lucide-vue-next'
import { ToastClose } from 'radix-vue'
import { computed } from 'vue'

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
})

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})
</script>

<template>
  <ToastClose
    v-bind="delegatedProps"
    :class="
      cn(
        'absolute right-2 top-2 rounded-md p-1 text-foreground/50 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-hidden focus:ring-2 group-hover:opacity-100 group-[.destructive]:text-red-300 hover:group-[.destructive]:text-red-50 focus:group-[.destructive]:ring-red-400 focus:group-[.destructive]:ring-offset-red-600',
        props.class,
      )
    "
  >
    <X class="size-4" />
  </ToastClose>
</template>
