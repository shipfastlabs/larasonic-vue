<script setup>
import { computed, useSlots } from 'vue'

defineEmits(['submitted'])

const hasActions = computed(() => !!useSlots().actions)
</script>

<template>
  <div class="md:grid md:grid-cols-3 md:gap-6">
    <div class="flex justify-between md:col-span-1">
      <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium">
          <slot name="title" />
        </h3>

        <p class="mt-1 text-sm">
          <slot name="description" />
        </p>
      </div>

      <div class="px-4 sm:px-0">
        <slot name="aside" />
      </div>
    </div>

    <div class="mt-5 md:col-span-2 md:mt-0">
      <form @submit.prevent="$emit('submitted')">
        <div class="rounded-lg border bg-background px-4 py-5 shadow-sm sm:p-6">
          <div class="grid grid-cols-6 gap-6">
            <slot name="form" />
          </div>
        </div>

        <div
          v-if="hasActions"
          class="flex items-center justify-end bg-background py-3 text-end sm:rounded-b-md"
        >
          <slot name="actions" />
        </div>
      </form>
    </div>
  </div>
</template>
