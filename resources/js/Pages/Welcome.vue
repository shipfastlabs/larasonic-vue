<script setup>
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import FeaturesCard from '@/components/FeaturesCard.vue'
import PricingCard from '@/components/PricingCard.vue'
import Terminal from '@/components/Terminal.vue'
import Accordion from '@/components/ui/accordion/Accordion.vue'
import AccordionContent from '@/components/ui/accordion/AccordionContent.vue'
import AccordionItem from '@/components/ui/accordion/AccordionItem.vue'
import AccordionTrigger from '@/components/ui/accordion/AccordionTrigger.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Button from '@/components/ui/button/Button.vue'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags.js'
import WebLayout from '@/layouts/WebLayout.vue'

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
    icon: '🚀',
    title: '10x Dev Experience',
    description: 'Ship faster with opinionated Laravel Pint, maximum PHPStan level, and Rector for enhanced code quality and developer productivity.',
  },
  {
    icon: '🐳',
    title: 'Production Docker Ready',
    description: 'Optimized Docker images with Laravel Octane for lightning-fast development and deployment.',
  },
  {
    icon: '🔑',
    title: 'Advanced Authentication',
    description: 'Complete authentication system with social login, and role-based access control.',
  },
  {
    icon: '💳',
    title: 'Payment Ready',
    description: 'Integrated Laravel Cashier for subscription billing and payment processing so you can focus on building your product.',
  },
  {
    icon: '🌐',
    title: 'API Ready',
    description: 'RESTful API endpoints with Laravel Sanctum authentication and comprehensive documentation.',
  },
  {
    icon: '🎨',
    title: 'Customizable UI',
    description: 'Built with shadcn/ui components, making UI customization a breeze. Easily modify themes, styles, and components to match your brand.',
  },
  {
    icon: '🧠',
    title: 'AI Integration Ready',
    description: 'Pre-configured LLM integrations for OpenAI, Anthropic, and more. Build AI-powered features into your app with minimal setup.',
  },
  {
    icon: '📊',
    title: 'FilamentPHP Admin',
    description: 'Beautiful admin panel powered by FilamentPHP with CRUD operations, charts, and detailed analytics.',
  },
  {
    icon: '✨',
    title: 'Evolving Features',
    description: 'This is just the beginning. Regular updates bring new features, integrations, and improvements to supercharge your development.',
  },
]

const pricingFeatures = [
  'Production-ready Docker setup',
  'Advanced authentication system',
  'AI Integrations',
  'Payment integration ready',
  'API endpoints with Sanctum',
  'Comprehensive documentation',
]
const sponsorLinks = {
  github: 'https://github.com/sponsors/pushpak1300',
  x: 'https://x.com/pushpak1300',
}

const faqItems = [
  {
    value: 'item-1',
    title: 'Is Larasonic really free?',
    content: 'Yes! Larasonic is completely free and open source under the MIT license. You can use it for personal or commercial projects without any restrictions. Feel free to star the repo for showing your intrest.',
  },
  {
    value: 'item-2',
    title: 'How can I contribute?',
    content: 'You can contribute by submitting pull requests, reporting bugs, suggesting features, or helping with documentation. Every contribution is valuable!',
  },
  {
    value: 'item-3',
    title: 'Why should I sponsor?',
    content: 'Sponsoring helps ensure the project\'s long-term sustainability. Your support enables continued maintenance, new features, and improvements that benefit the entire community.',
  },
]

const githubUrl = 'https://github.com/shipfastlabs/larasonic-vue'
</script>

<template>
  <WebLayout :can-login="canLogin" :can-register="canRegister">
    <!-- Hero Section -->
    <section class="relative overflow-hidden border-b bg-background py-20 sm:py-32">
      <div class="container mx-auto px-4 text-center">
        <!-- Badge -->
        <div class="mb-8 inline-flex justify-center">
          <Badge variant="outline" class="rounded-full border bg-primary/10 px-4 py-1 text-xs sm:text-sm">
            ✨ Using PHP 8.3+, Laravel 11, Inertia 2.0 and Tailwind CSS 4+
          </Badge>
        </div>

        <!-- Main Heading -->
        <div class="mx-auto max-w-4xl">
          <h1
            class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl lg:text-7xl"
            :style="{ contain: 'layout paint' }"
          >
            <span class="block text-foreground">Modern Laravel</span>
            <span
              class="mt-2 block bg-linear-to-r from-red-500 via-purple-500 to-indigo-500 bg-clip-text text-transparent"
            >
              SaaS Starter Kit
            </span>
          </h1>
        </div>

        <!-- Subtitle - Add priority hint -->
        <p
          class="mx-auto mt-6 max-w-2xl text-center text-base text-muted-foreground sm:text-lg md:text-xl"
          :style="{ contain: 'layout paint' }"
          fetchpriority="high"
        >
          Ship faster production-ready applications 10x faster with starter kit powered
          by Laravel Jetstream, Inertia V2, and Shadcn/ui.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex items-center justify-center gap-4 flex-row">
          <Button
            as="a" href="https://larasonic.com/dashboard" target="_blank" size="lg"
            class="w-full sm:w-auto"
          >
            View Demo
          </Button>
          <Button
            as="a" :href="githubUrl" target="_blank" size="lg" variant="outline"
            class="w-full sm:w-auto"
          >
            <Icon icon="lucide:github" class="size-4" aria-hidden="true" />
            Github
          </Button>
        </div>

        <!-- Trust Badges -->
        <div class="mt-16 sm:mt-24">
          <p class="text-sm text-muted-foreground">
            Trusted by developers worldwide
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
        Features ✨
      </h2>
      <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">
        Everything you need to ship faste to production without any hassle.
      </p>

      <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <FeaturesCard
          v-for="feature in features" :key="feature.title" :icon="feature.icon"
          :title="feature.title" :description="feature.description"
        />
      </div>
      <div class="mt-6 flex justify-center gap-2">
        <Button as="a" href="https://docs.larasonic.com" target="_blank" rel="noopener noreferrer">
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
            Proudly Open Source 🤑
          </h2>
          <p class="mx-auto mt-4 max-w-2xl text-center text-muted-foreground">
            Larasonic is and will always be open source. No hidden fees, no premium features.
          </p>
        </div>

        <!-- Pricing Card -->
        <PricingCard
          class="mx-auto mt-16" :features="pricingFeatures" :price="0" plan="What's included ?"
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
                  Follow Me
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
              Ready to ship faster?
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-lg">
              You're already blazing fast with Laravel.<br> Larasonic is about to make your shipping speed
              supersonic. 🚀
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
