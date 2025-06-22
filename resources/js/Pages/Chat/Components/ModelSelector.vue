<script setup>
import Label from '@/componentsui/label/Label.vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/componentsui/select'
import { ref, watch } from 'vue'

const props = defineProps({
  models: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['update:modelSelect'])
const selectedModel = ref(props.models[0])

watch(selectedModel, (newVal) => {
  emit('update:modelSelect', newVal)
})
</script>

<template>
  <Label for="model">Model</Label>
  <Select id="model" v-model="selectedModel">
    <SelectTrigger class="w-[180px]">
      <SelectValue :placeholder="models[0]" />
    </SelectTrigger>
    <SelectContent>
      <SelectGroup>
        <SelectItem
          v-for="model in models"
          :key="model"
          :value="model"
        >
          <span class="text-sm">{{ model }}</span>
        </SelectItem>
      </SelectGroup>
    </SelectContent>
  </Select>
</template>
