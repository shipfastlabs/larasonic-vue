<script setup>
import { Icon } from '@iconify/vue'
import { Deferred } from '@inertiajs/vue3'
import Separator from '@/components/ui/separator/Separator.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

import SubscriptionManager from '@/Pages/Subscriptions/Partials/SubscriptionManager.vue'
import InvoiceManager from './Partials/InvoiceManager.vue'

defineProps({
  activeSubscriptions: {
    type: Array,
    default: () => [],
  },
  availableSubscriptions: {
    type: Array,
    default: () => [],
  },
  activeInvoices: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <AppLayout title="Subscriptions">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight">
        Subscriptions
      </h2>
    </template>

    <div>
      <div class="max-w-7xl">
        <SubscriptionManager
          :active-subscriptions="activeSubscriptions"
          :available-subscriptions="availableSubscriptions" class="mt-10 sm:mt-0"
        />
      </div>

      <Deferred data="activeInvoices">
        <template #fallback>
          <Icon icon="lucide:loader" />
        </template>
        <div v-if="activeInvoices.length > 0" class="md:max-w-7xl">
          <Separator class="my-8 hidden sm:block" />
          <InvoiceManager :invoices="activeInvoices" class="mt-10 sm:mt-0" />
        </div>
      </Deferred>
    </div>
  </AppLayout>
</template>
