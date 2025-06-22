<script setup>
import StatsCard from '@/components/StatsCard.vue'
import AppLayout from '@/layouts/AppLayout.vue'

const stats = [
  {
    value: 'Larasonic Documentation',
    description: 'Check out our comprehensive documentation',
    link: 'https://docs.larasonic.com/introduction',
    icon: 'lucide:book-open',
  },
  {
    value: 'GitHub Repository',
    description: 'Star us on GitHub',
    link: 'https://github.com/shipfastlabs/larasonic-vue',
    icon: 'lucide:github',
  },
  {
    value: 'Roadmap',
    description: 'See what\'s coming next',
    link: 'https://github.com/shipfastlabs/larasonic-vue/discussions/categories/roadmap',
    icon: 'lucide:map',
  },
  {
    value: 'Join Us',
    description: 'Be part of our growing community',
    link: 'https://github.com/shipfastlabs/larasonic-vue/discussions',
    icon: 'lucide:users',
  },
]
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight">
        Dashboard
      </h2>
    </template>

    <div>
      <div class="flex flex-1 flex-col gap-4 pt-0">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
          <StatsCard
            v-for="stat in stats"
            :key="stat.title"
            v-bind="stat"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
