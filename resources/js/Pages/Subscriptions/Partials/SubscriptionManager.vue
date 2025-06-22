<script setup>
import { Icon } from '@iconify/vue'
import { inject } from 'vue'
import PricingCard from '@/components/PricingCard.vue'
import Alert from '@/components/ui/alert/Alert.vue'
import AlertTitle from '@/components/ui/alert/AlertTitle.vue'
import Button from '@/components/ui/button/Button.vue'

defineProps({
  activeSubscriptions: {
    type: Array,
    default: () => [],
  },
  availableSubscriptions: {
    type: Array,
    default: () => [],
  },
})

const route = inject('route')
</script>

<template>
  <div class="mt-5 md:col-span-2 md:mt-0">
    <header class="pb-4">
      <h3 class="text-lg font-medium">
        Manage Subscriptions
      </h3>
    </header>

    <div v-if="activeSubscriptions.length === 0" class="flex flex-col space-y-4">
      <Alert class="text-md">
        <Icon icon="lucide:triangle-alert" class="size-4" />
        <AlertTitle>
          You are not subscribed to any plan. Subscribe to a plan to continue.
        </AlertTitle>
      </Alert>

      <PricingCard
        v-for="subscription in availableSubscriptions" :key="subscription.price_id"
        :plan="subscription.plan" :price="subscription.price" :description="subscription.description"
        :features="subscription.features" button-text="Subscribe"
        :button-href="route('subscriptions.show', { subscription: subscription.price_id })"
      />
    </div>

    <div v-else>
      <Alert>
        <AlertTitle class="flex items-center justify-between">
          You are currently on the {{ activeSubscriptions[0].type }} Plan.
          <Button as="a" :href="route('subscriptions.index')">
            Manage Subscription
          </Button>
        </AlertTitle>
      </Alert>
    </div>

    <footer class="mt-2">
      <p class="text-sm text-muted-foreground">
        🔒 Subscriptions are managed by Stripe securely.
      </p>
    </footer>
  </div>
</template>
