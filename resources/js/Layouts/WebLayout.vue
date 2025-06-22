<script setup>
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import { useColorMode } from '@vueuse/core'
import { ref } from 'vue'
import Button from '@/components/ui/button/Button.vue'

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
})

const mode = useColorMode({
  attribute: 'class',
  modes: {
    light: '',
    dark: 'dark',
  },
})

const navLinks = [
  { label: 'Features', href: '/#features', external: false },
  { label: 'Pricing', href: '/#pricing', external: false },
  { label: 'Docs', href: 'https://docs.larasonic.com/introduction', external: true },
]

const githubUrl = 'https://github.com/shipfastlabs/larasonic-vue'
const twitterUrl = 'https://x.com/pushpak1300?ref=larasonic'

const isMenuOpen = ref(false)

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value
}
</script>

<template>
  <div class="min-h-screen">
    <header
      class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur-sm supports-backdrop-filter:bg-background/60"
    >
      <div class="container flex h-16 items-center justify-between">
        <div class="flex items-center">
          <a class="flex items-center space-x-2" href="/" :aria-label="$page.props.name">
            <Icon icon="lucide:rocket" class="h-6 w-6" aria-hidden="true" />
            <span class="hidden font-bold sm:inline-block">
              {{ $page.props.name }}
            </span>
          </a>
          <nav class="hidden md:flex items-center space-x-6 text-sm font-medium sm:ml-4">
            <a
              v-for="link in navLinks" :key="link.href" :href="link.href"
              class="transition-colors hover:text-foreground/80" :class="[
                link.href.startsWith('http') ? '' : 'text-foreground/60',
              ]" :target="link.href.startsWith('http') ? '_blank' : undefined"
              :rel="link.href.startsWith('http') ? 'noreferrer' : undefined"
            >
              {{ link.label }}
            </a>
          </nav>
        </div>
        <div class="flex items-center space-x-4">
          <div class="hidden sm:flex space-x-2">
            <template v-if="!$page.props.auth.user">
              <Button variant="outline" :as="Link" href="/login" prefetch="mount">
                Login
              </Button>
              <Button variant="outline" :as="Link" href="/register" prefetch="mount">
                Register
              </Button>
            </template>
            <Button v-else variant="outline" :as="Link" href="/dashboard" prefetch="mount">
              Dashboard
            </Button>
          </div>
          <Button
            variant="ghost" size="icon" aria-label="Toggle Theme"
            @click="mode = mode === 'dark' ? 'light' : 'dark'"
          >
            <Icon
              class="text-muted-foreground h-6 w-6"
              :icon="mode === 'dark' ? 'lucide:sun' : 'lucide:moon'"
            />
          </Button>
          <a
            :href="githubUrl" target="_blank" rel="noreferrer" class="hidden sm:inline-block"
            aria-label="GitHub"
          >
            <Icon icon="mdi:github" class="h-5 w-5" />
          </a>
          <Button class="md:hidden" variant="ghost" size="icon" aria-label="Toggle menu" @click="toggleMenu">
            <Icon :icon="isMenuOpen ? 'lucide:x' : 'lucide:menu'" class="h-6 w-6" aria-hidden="true" />
          </Button>
        </div>
      </div>
      <!-- Mobile menu -->
      <div v-show="isMenuOpen" class="md:hidden border-t">
        <nav class="flex flex-col p-4 space-y-4">
          <a
            v-for="link in navLinks" :key="link.href" :href="link.href"
            class="text-sm font-medium transition-colors hover:text-foreground/80"
            :target="link.href.startsWith('http') ? '_blank' : undefined"
            :rel="link.href.startsWith('http') ? 'noreferrer' : undefined" @click="toggleMenu"
          >
            {{ link.label }}
          </a>
          <template v-if="!$page.props.auth.user">
            <Button
              variant="outline" :as="Link" href="/login" class="w-full" prefetch="mount"
              @click="toggleMenu"
            >
              Login
            </Button>
            <Button
              variant="outline" :as="Link" href="/register" class="w-full" prefetch="mount"
              @click="toggleMenu"
            >
              Register
            </Button>
          </template>
          <Button
            v-else variant="outline" :as="Link" href="/dashboard" class="w-full"
            prefetch="mount" @click="toggleMobileMenu"
          >
            Dashboard
          </Button>
          <a
            :href="githubUrl" target="_blank" rel="noreferrer"
            class="flex items-center space-x-2 text-sm font-medium" @click="toggleMobileMenu"
          >
            <Icon icon="mdi:github" class="h-5 w-5" />
            <span>GitHub</span>
          </a>
        </nav>
      </div>
    </header>

    <slot />

    <!-- Footer -->
    <footer class="border-t">
      <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
          <p class="text-sm flex items-center gap-2 text-center sm:text-left">
            <Icon icon="lucide:rocket" class="size-6" aria-hidden="true" />
            Crafted by<a
              class="underline" :href="twitterUrl" target="_blank"
              rel="noopener noreferrer"
            >Pushpak.
            </a>
            <span>
              Hosted On <a
                class="underline" href="https://sevalla.com/?ref=larasonic" target="_blank"
                rel="noopener noreferrer"
              >
                Sevalla
              </a>
              ❤️
            </span>
          </p>
          <div class="flex gap-4">
            <Icon
              class="text-muted-foreground" :icon="mode === 'dark' ? 'lucide:sun' : 'lucide:moon'"
              @click="mode = mode === 'dark' ? 'light' : 'dark'"
            />

            <a
              :href="githubUrl" target="_blank" rel="noopener noreferrer"
              class="text-muted-foreground hover:text-foreground" aria-label="GitHub"
            >
              <Icon icon="mdi:github" class="h-5 w-5" aria-hidden="true" />
            </a>
            <a
              :href="twitterUrl" target="_blank" rel="noopener noreferrer"
              class="text-muted-foreground hover:text-foreground" aria-label="Twitter"
            >
              <Icon icon="ri:twitter-x-line" class="h-5 w-5" aria-hidden="true" />
            </a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>
