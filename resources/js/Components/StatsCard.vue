<script setup>
import Card from '@/Components/shadcn/ui/card/Card.vue'
import CardContent from '@/Components/shadcn/ui/card/CardContent.vue'
import CardHeader from '@/Components/shadcn/ui/card/CardHeader.vue'
import CardTitle from '@/Components/shadcn/ui/card/CardTitle.vue'
import { Icon } from '@iconify/vue'

defineProps({
  value: {
    type: [String, Number],
    required: true,
  },
  description: {
    type: String,
    default: '',
  },
  link: {
    type: String,
    default: '',
  },
  icon: {
    type: String,
    default: 'info',
  },
})
</script>

<template>
  <a
    :href="link"
    target="_blank"
    rel="noopener"
  >
    <Card class="hover:shadow-sm">
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-medium">
          <Icon
            :icon="icon"
            class="size-8"
          />
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div class="text-xl font-bold text-primary">
          {{ value }}
        </div>
        <p class="text-muted-foreground">
          {{ description }}
        </p>
      </CardContent>
    </Card>
  </a>
</template>
