<script setup>
import { ref } from 'vue'

const screenshotUrl = ref('https://via.placeholder.com/800x400') // Placeholder for the application screenshot
</script>

<template>
  <div class="w-full rounded-lg shadow-lg overflow-hidden border border-muted relative">
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <div class="flex space-x-2">
          <div class="size-3 rounded-full bg-red-500" />
          <div class="size-3 rounded-full bg-yellow-500" />
          <div class="size-3 rounded-full bg-green-500" />
        </div>
      </div>
      <div class="relative">
        <img :src="screenshotUrl" alt="Application Screenshot" class="w-full h-auto" />
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles if needed */
</style>
