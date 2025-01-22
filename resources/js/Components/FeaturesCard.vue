<script setup>
defineProps({
  icon: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div class="flex flex-col items-left text-left rounded-lg border bg-card p-6 shadow-xs">
    <div class="h-12 w-12 text-primary text-3xl">
      {{ icon }}
    </div>
    <h3 class="mt-4 text-xl font-semibold">
      {{ title }}
    </h3>
    <p class="mt-2 text-muted-foreground">
      {{ description }}
    </p>
  </div>
</template>
