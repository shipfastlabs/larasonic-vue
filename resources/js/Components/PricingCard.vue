<script setup>
import { CheckIcon } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Card, CardFooter } from '@/components/ui/card'

defineProps({
  features: {
    type: Array,
    required: false,
    default: () => [
      'Production-ready Docker setup',
      'Advanced authentication system',
      'API endpoints with Sanctum',
      'Comprehensive documentation',
      'Regular updates & improvements',
      'Best In Class IDE support',
    ],
  },
  price: {
    type: Number,
    default: 19,
  },
  plan: {
    type: String,
    default: 'PRO',
  },
  description: {
    type: String,
    default: 'Perfect for growing businesses',
  },
  billingPeriod: {
    type: String,
    default: 'Billed Monthly',
  },
  buttonText: {
    type: String,
    default: 'Get Started',
  },
  buttonVariant: {
    type: String,
    default: 'default',
  },
  buttonHref: {
    type: String,
    default: '/login',
  },
  className: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <Card class="w-full" :class="className">
    <div class="grid w-full items-start gap-10 rounded-lg border p-8 md:grid-cols-[1fr_200px]">
      <div class="grid gap-4 sm:gap-6">
        <slot name="header">
          <h3 class="text-lg sm:text-xl font-bold md:text-2xl">
            {{ plan }}
          </h3>
          <p class="text-xs sm:text-sm text-muted-foreground">
            {{ description }}
          </p>
        </slot>
        <slot name="features">
          <ul class="grid gap-2 sm:gap-3 text-xs sm:text-sm text-muted-foreground sm:grid-cols-2">
            <li v-for="feature in features" :key="feature" class="flex items-center">
              <CheckIcon class="mr-2 h-3 w-3 sm:h-4 sm:w-4" />
              {{ feature }}
            </li>
          </ul>
        </slot>
      </div>
      <div class="flex flex-col gap-3 sm:gap-4 text-center justify-around max-w-full h-full mt-4 md:mt-0">
        <slot name="pricing">
          <div>
            <h4 class="text-4xl sm:text-4xl md:text-6xl lg:text-7xl font-bold">
              ${{ price }}
            </h4>
            <p class="text-xs sm:text-sm font-medium text-muted-foreground">
              {{ billingPeriod }}
            </p>
          </div>
        </slot>
        <slot name="action">
          <Button
            as="a"
            :variant="buttonVariant"
            :href="buttonHref"
            class="w-full text-xs sm:text-sm"
          >
            {{ buttonText }}
          </Button>
        </slot>
      </div>
    </div>

    <CardFooter v-if="$slots.footer" class="p-4">
      <slot name="footer" />
    </CardFooter>
  </Card>
</template>
