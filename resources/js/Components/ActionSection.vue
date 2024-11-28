<script setup>
import SectionTitle from './SectionTitle.vue'
</script>

<template>
  <div class="md:grid md:grid-cols-3 md:gap-6">
    <SectionTitle>
      <template #title>
        <slot name="title" />
      </template>
      <template #description>
        <slot name="description" />
      </template>
    </SectionTitle>

    <div class="mt-5 md:col-span-2 md:mt-0">
      <div class="border px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <slot name="content" />
      </div>
    </div>
  </div>
</template>
