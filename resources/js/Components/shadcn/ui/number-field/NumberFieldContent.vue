<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div
    :class="
      cn(
        'relative has-data-[slot=increment]:*:data-[slot=input]:pr-5 has-data-[slot=decrement]:*:data-[slot=input]:pl-5',
        props.class,
      )
    "
  >
    <slot />
  </div>
</template>
