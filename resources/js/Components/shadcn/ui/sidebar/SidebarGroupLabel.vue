<script setup>
import { cn } from '@/lib/utils'
import { Primitive } from 'radix-vue'

const props = defineProps({
  as: { type: String, default: undefined },
  asChild: { type: Boolean, default: false },
  class: { type: null, required: false }
})
</script>

<template>
  <Primitive
    data-sidebar="group-label"
    :as="as"
    :as-child="asChild"
    :class="cn(
      'duration-200 flex h-8 shrink-0 items-center rounded-md px-2 text-xs font-medium text-sidebar-foreground/70 outline-hidden ring-sidebar-ring transition-[margin,opa] ease-linear focus-visible:ring-2 [&>svg]:size-4 [&>svg]:shrink-0',
      'group-data-[collapsible=icon]:-mt-8 group-data-[collapsible=icon]:opacity-0',
      props.class)"
  >
    <slot />
  </Primitive>
</template>
