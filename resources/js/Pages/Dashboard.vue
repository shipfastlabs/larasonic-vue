<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow-xl sm:rounded-lg">
          <div>
            <div class="border-b border-secondary p-6 lg:p-8">
              <ApplicationLogo class="block w-auto" />

              <h1 class="mt-8 text-2xl font-medium">
                Welcome to your Larasonic application!
              </h1>

              <p class="mt-6 leading-relaxed">
                LaraSonic is Opiniated Vue Inertia Tailwind Fullstack Kit.
                With This You can build apps 10x Faster.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
