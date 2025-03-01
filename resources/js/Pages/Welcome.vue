<script setup>
import FeaturesCard from '@/Components/FeaturesCard.vue'
import PricingCard from '@/Components/PricingCard.vue'
import Accordion from '@/Components/shadcn/ui/accordion/Accordion.vue'
import AccordionContent from '@/Components/shadcn/ui/accordion/AccordionContent.vue'
import AccordionItem from '@/Components/shadcn/ui/accordion/AccordionItem.vue'
import AccordionTrigger from '@/Components/shadcn/ui/accordion/AccordionTrigger.vue'
import Badge from '@/Components/shadcn/ui/badge/Badge.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Terminal from '@/Components/Terminal.vue'
import { useSeoMetaTags } from '@/Composables/useSeoMetaTags.js'
import WebLayout from '@/Layouts/WebLayout.vue'
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  seo: {
    type: Object,
    default: () => null,
  },
})

useSeoMetaTags(props.seo)

const features = [
  {
    icon: '📚',
    title: 'Comprehensive Course Management',
    description: 'Easily manage your courses, assignments, and grades with our intuitive interface designed for both faculty and students.',
  },
  {
    icon: '👩‍🏫',
    title: 'Faculty Collaboration Tools',
    description: 'Empower faculty members with tools for collaboration, communication, and resource sharing to enhance the learning experience.',
  },
  {
    icon: '🗓️',
    title: 'Streamlined Scheduling',
    description: 'Access a centralized calendar for classes, exams, and events, ensuring you never miss an important date.',
  },
  {
    icon: '💬',
    title: 'Real-time Communication',
    description: 'Stay connected with peers and instructors through integrated messaging and notification systems.',
  },
  {
    icon: '📊',
    title: 'Performance Tracking',
    description: 'Monitor your academic progress with detailed analytics and reports, helping you stay on track for success.',
  },
  {
    icon: '🔒',
    title: 'Secure Access',
    description: 'Your data is protected with top-notch security measures, ensuring a safe environment for all users.',
  },
  {
    icon: '🌐',
    title: 'Accessible Anywhere',
    description: "Access the portal from any device, whether you're at home, in class, or on the go, making learning flexible and convenient.",
  },
  {
    icon: '🎓',
    title: 'Community Engagement',
    description: 'Join a vibrant community of learners and educators, fostering collaboration and support throughout your academic journey.',
  },
  {
    icon: '✨',
    title: 'Continuous Improvement',
    description: 'We are committed to evolving our platform based on user feedback, ensuring that DCCPHub meets the needs of our community.',
  },
]

const pricingFeatures = [
  'Free access for all students and faculty',
  'Comprehensive support and resources',
  'Regular updates and new features',
  'User-friendly interface',
  'Dedicated community forums',
]
const sponsorLinks = {
  github: 'https://github.com/sponsors/pushpak1300',
  x: 'https://x.com/pushpak1300',
}

const faqItems = [
  {
    value: 'item-1',
    title: 'Is DCCPHub really free?',
    content: 'Yes! DCCPHub is completely free for all students and faculty members, providing essential tools without any hidden fees.',
  },
  {
    value: 'item-2',
    title: 'How can I contribute?',
    content: 'You can contribute by providing feedback, suggesting features, or participating in community discussions. Your input is invaluable!',
  },
  {
    value: 'item-3',
    title: 'Why should I use DCCPHub?',
    content: 'DCCPHub simplifies your academic life, providing all the tools you need in one place, enhancing your learning experience and productivity.',
  },
]

const githubUrl = 'https://github.com/pushpak1300/dccphub'
</script>

<template>
  <WebLayout :can-login="canLogin" :can-register="canRegister">
    <!-- Hero Section -->
    <section class="relative overflow-hidden border-b bg-background py-20 sm:py-32">
      <div class="container mx-auto px-4 text-center">
        <!-- Badge -->
        <div class="mb-8 inline-flex justify-center">
          <Badge variant="outline" class="rounded-full border bg-primary/10 px-4 py-1 text-xs sm:text-sm">
            ✨ Empowering Education with DCCPHub
          </Badge>
        </div>

        <!-- Main Heading -->
        <div class="mx-auto max-w-4xl">
          <h1
            class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl lg:text-7xl"
            :style="{ contain: 'layout paint' }"
          >
            <span class="block text-foreground">DCCPHub</span>
            <span
              class="mt-2 block bg-linear-to-r from-blue-500 via-green-500 to-yellow-500 bg-clip-text text-transparent"
            >
              Your School Portal
            </span>
          </h1>
        </div>

        <!-- Subtitle - Add priority hint -->
        <p
          class="mx-auto mt-6 max-w-2xl text-center text-base text-muted-foreground sm:text-lg md:text-xl"
          :style="{ contain: 'layout paint' }"
          fetchpriority="high"
        >
          Seamlessly manage your academic journey with DCCPHub, designed for both faculty and students to enhance learning and collaboration.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex items-center justify-center gap-4 flex-row">
          <Button
            as="a" href="https://dccphub.com/dashboard" target="_blank" size="lg"
            class="w-full sm:w-auto"
          >
            Explore DCCPHub
          </Button>
          <Button
            as="a" :href="githubUrl" target="_blank" size="lg" variant="outline"
            class="w-full sm:w-auto"
          >
            <Icon icon="lucide:github" class="size-4" aria-hidden="true" />
            GitHub
          </Button>
        </div>

        <!-- Trust Badges -->
        <div class="mt-16 sm:mt-24">
          <p class="text-sm text-muted-foreground">
            Trusted by educators and students alike
          </p>
          <div class="mt-4 flex flex-wrap items-center justify-center gap-6 sm:gap-8">
            <Icon
              icon="logos:laravel"
              class="size-8 opacity-75 grayscale transition-all hover:opacity-100 hover:grayscale-0"
            />
            <Icon
              icon="logos:vue"
              class="size-8 opacity-75 grayscale transition-all hover:opacity-100 hover:grayscale-0"
            />
            <Icon
              icon="simple-icons:inertia"
              class="size-8 opacity-75 grayscale transition-all hover:opacity-100 hover:grayscale-0 text-purple-500"
            />
            <Icon
              icon="logos:tailwindcss-icon"
              class="size-8 opacity-75 grayscale transition-all hover:opacity-100 hover:grayscale-0"
            />
          </div>
        </div>
      </div>

      <!-- Background Effects -->
      <div
        class="absolute inset-0 -z-10 h-full w-full bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:14px_24px]"
      />
      <div
        class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-primary/20 opacity-20 blur-[100px]"
      />
    </section>
    <!-- Features Grid -->
    <section id="features" class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
      <h2 class="text-center text-2xl font-bold tracking-tight sm:text-4xl">
        Features of DCCPHub 🌟
      </h2>
      <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">
        Discover the powerful features designed to enhance your educational experience.
      </p>

      <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <FeaturesCard
          v-for="feature in features" :key="feature.title" :icon="feature.icon"
          :title="feature.title" :description="feature.description"
        />
      </div>
      <div class="mt-6 flex justify-center gap-2">
        <Button as="a" href="https://docs.dccphub.com" target="_blank" rel="noopener noreferrer">
          <Icon icon="lucide:book-open" class="size-4" aria-hidden="true" />
          Documentation
        </Button>
        <Button
          variant="secondary" as="a" :href="`${githubUrl}/discussions/categories/roadmap`" target="_blank"
          rel="noopener noreferrer"
        >
          <Icon icon="lucide:construction" class="size-4" aria-hidden="true" />
          Roadmap
        </Button>
      </div>
    </section>

    <section id="pricing" class="border-t">
      <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mx-auto max-w-3xl text-center">
          <h2 class="text-center text-2xl font-bold tracking-tight sm:text-4xl">
            Always Free for Students and Faculty 🎓
          </h2>
          <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">
            DCCPHub is committed to providing essential tools for free, ensuring accessibility for all.
          </p>
        </div>

        <!-- Pricing Card -->
        <PricingCard
          class="mx-auto mt-16" :features="pricingFeatures" :price="0" plan="What's included?"
          billing-period="Free Forever"
        >
          <template #action>
            <Button :as="Link" :href="route('dashboard')">
              Get Started
            </Button>
          </template>
          <template #footer>
            <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <p class="text-sm">
                Want to support the development?
              </p>
              <div class="flex gap-4">
                <Button variant="outline" as="a" :href="sponsorLinks.github" target="_blank">
                  <Icon icon="mdi:github" class="mr-2 size-4" aria-hidden="true" />
                  Sponsor
                </Button>
                <Button variant="outline" as="a" :href="sponsorLinks.x" target="_blank">
                  <Icon icon="ri:twitter-x-line" class="mr-2 size-4" aria-hidden="true" />
                  Follow Us
                </Button>
              </div>
            </div>
          </template>
        </PricingCard>
        <!-- FAQ Section -->
        <div class="mx-auto mt-16 text-center">
          <h2 class="text-2xl font-bold">
            Frequently Asked Questions
          </h2>
          <Accordion type="single" class="mt-8 w-full text-left" collapsible default-value="item-1">
            <AccordionItem v-for="item in faqItems" :key="item.value" :value="item.value">
              <AccordionTrigger class="text-lg">
                {{ item.title }}
              </AccordionTrigger>
              <AccordionContent>
                {{ item.content }}
              </AccordionContent>
            </AccordionItem>
          </Accordion>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="border-t">
      <div class="container mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-2xl px-6 py-12 sm:p-16">
          <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight sm:text-6xl">
              Ready to Enhance Your Learning Experience?
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-lg">
              Join DCCPHub today and unlock the full potential of your academic journey. 🚀
            </p>
            <div class="mt-8 flex justify-center gap-4">
              <Button as="a" :href="githubUrl" target="_blank" rel="noopener noreferrer">
                View on GitHub
              </Button>
            </div>
          </div>
        </div>
        <div class="mx-auto w-full sm:w-2/3 items-center justify-center">
          <Terminal />
        </div>
      </div>
    </section>
  </WebLayout>
</template>
