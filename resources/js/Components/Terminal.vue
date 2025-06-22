<script setup>
import { Icon } from '@iconify/vue'
import { onMounted, ref } from 'vue'

const terminalStep = ref(0)
const copied = ref(false)
const terminalSteps = [
  'laravel new larasonic --using=shipfastlabs/larasonic-vue',
]

onMounted(() => {
  const timer = setInterval(() => {
    if (terminalStep.value < terminalSteps.length - 1) {
      terminalStep.value++
    }
    else {
      clearInterval(timer)
    }
  }, 500)
})

function copyToClipboard() {
  navigator.clipboard.writeText(terminalSteps.join(' && '))
  copied.value = true
  setTimeout(() => (copied.value = false), 2000)
}
</script>

<template>
  <div class="w-full rounded-lg shadow-lg overflow-hidden border border-muted text-white font-mono text-sm relative">
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <div class="flex space-x-2">
          <div class="size-3 rounded-full bg-red-500" />
          <div class="size-3 rounded-full bg-yellow-500" />
          <div class="size-3 rounded-full bg-green-500" />
        </div>
        <button
          class="text-gray-400 hover:text-white transition-colors"
          aria-label="Copy to clipboard"
          @click="copyToClipboard"
        >
          <Icon :icon="copied ? 'lucide:check' : 'lucide:copy'" class="h-5 w-5" />
        </button>
      </div>
      <div class="space-y-2">
        <div
          v-for="(step, index) in terminalSteps"
          :key="index"
          :class="{ 'opacity-0': index > terminalStep, 'opacity-100': index <= terminalStep }"
          class="transition-opacity duration-300 text-foreground"
        >
          <span class="text-green-400 dark:text-green-500">$</span> {{ step }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.opacity-0 {
  opacity: 0;
}
.opacity-100 {
  opacity: 1;
}
</style>
