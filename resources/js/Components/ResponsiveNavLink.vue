<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import Button from './shadcn/ui/button/Button.vue'

const props = defineProps({
  active: Boolean,
  href: String,
  as: String,
})

const classes = computed(() => {
  return props.active
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 text-start font-medium focus:outline-none transition duration-150 ease-in-out'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start font-medium focus:outline-none transition duration-150 ease-in-out'
})
</script>

<template>
  <div>
    <Button v-if="as === 'button'" :class="classes" class="w-full text-start">
      <slot />
    </Button>

    <a v-else-if="as === 'a'" :class="classes" class="w-full text-start" :href="href">
      <slot />
    </a>

    <Link v-else :href="href" :class="classes">
      <slot />
    </Link>
  </div>
</template>
