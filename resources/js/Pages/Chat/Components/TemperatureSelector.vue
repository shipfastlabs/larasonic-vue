<script setup>
import { ref } from 'vue'
import {
  HoverCard,
  HoverCardContent,
  HoverCardTrigger,
} from '@/components/ui/hover-card'
import { Label } from '@/components/ui/label'
import { Slider } from '@/components/ui/slider'

const props = defineProps({
  defaultValue: {
    type: Array,
    default: () => [0.5],
  },
})

const value = ref(props.defaultValue)
</script>

<template>
  <div class="grid gap-2 pt-2">
    <HoverCard :open-delay="200">
      <HoverCardTrigger as-child>
        <div class="grid gap-4">
          <div class="flex items-center justify-between">
            <Label for="temperature">Temperature</Label>
            <span
              class="w-12 rounded-md border border-transparent px-2 py-0.5 text-right text-sm text-muted-foreground hover:border-border"
            >
              {{ value[0] }}
            </span>
          </div>
          <Slider
            id="temperature" v-model="value" :min="0" :max="1" :step="0.1"
            class="[&_[role=slider]]:h-4 [&_[role=slider]]:w-4" aria-label="Temperature"
          />
        </div>
      </HoverCardTrigger>
      <HoverCardContent align="start" class="w-[260px] text-sm" side="left">
        Controls randomness: lowering results in less random completions. As
        the temperature approaches zero, the model will become deterministic
        and repetitive.
      </HoverCardContent>
    </HoverCard>
  </div>
</template>
