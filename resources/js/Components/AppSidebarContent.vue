<script setup>
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import { useColorMode } from '@vueuse/core'
import { computed, inject } from 'vue'
import {
  SidebarContent,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar'

const route = inject('route')
const mode = useColorMode({
  attribute: 'class',
  modes: { light: '', dark: 'dark' },
})

const navigationConfig = [
  {
    label: 'Platform',
    items: [
      { name: 'Dashboard', icon: 'lucide:layout-dashboard', route: 'dashboard' },
      { name: 'Settings', icon: 'lucide:settings', route: 'profile.show' },
      { name: 'Chat', icon: 'lucide:message-circle', route: 'chat.index' },
    ],
  },
  {
    label: 'API',
    items: [
      { name: 'API Tokens', icon: 'lucide:key', route: 'api-tokens.index' },
      { name: 'API Documentation', icon: 'lucide:book-heart', route: 'scribe', external: true },
    ],
  },
  {
    label: null,
    class: 'mt-auto',
    items: [
      {
        name: 'Support',
        icon: 'lucide:life-buoy',
        href: 'https://github.com/shipfastlabs/larasonic-vue/issues',
        external: true,
      },
      {
        name: 'Documentation',
        icon: 'lucide:book-marked',
        href: 'https://docs.larasonic.com',
        external: true,
      },
    ],
  },
]

const isDarkMode = computed(() => mode.value === 'dark')

function renderLink(item) {
  if (item.external) {
    return {
      is: 'a',
      href: item.href || route(item.route),
      target: '_blank',
    }
  }
  return {
    is: Link,
    href: route(item.route),
  }
}
</script>

<template>
  <SidebarContent>
    <SidebarGroup v-for="(group, index) in navigationConfig" :key="index" :class="group.class">
      <SidebarGroupLabel v-if="group.label">
        {{ group.label }}
      </SidebarGroupLabel>
      <SidebarMenu>
        <SidebarMenuItem
          v-for="item in group.items"
          :key="item.name"
          :class="{ 'font-semibold text-primary bg-secondary rounded': !item.external && route().current(item.route) }"
        >
          <SidebarMenuButton as-child>
            <component v-bind="renderLink(item)" :is="item.external ? 'a' : Link" prefetch>
              <Icon :icon="item.icon" />
              {{ item.name }}
            </component>
          </SidebarMenuButton>
        </SidebarMenuItem>
        <SidebarMenuItem v-if="index === navigationConfig.length - 1">
          <SidebarMenuButton @click="mode = isDarkMode ? 'light' : 'dark'">
            <Icon :icon="isDarkMode ? 'lucide:moon' : 'lucide:sun'" />
            {{ isDarkMode ? 'Dark' : 'Light' }} Mode
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarGroup>
  </SidebarContent>
</template>
