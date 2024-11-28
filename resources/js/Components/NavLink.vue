<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  href: String,
  active: Boolean,
})

const classes = computed(() => {
  return props.active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
})
</script>

<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>
