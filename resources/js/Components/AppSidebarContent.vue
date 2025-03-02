<script setup>
import {
  SidebarContent,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/Components/shadcn/ui/sidebar'
import { Icon } from '@iconify/vue'
import { Link } from '@inertiajs/vue3'
import { useColorMode } from '@vueuse/core'
import { computed, inject } from 'vue'

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
      { name: 'Schedule', icon: 'lucide:calendar', route: 'schedule.index' },
      { name: 'Tuition', icon: 'lucide:banknote', route: 'tuition.index' },
      { name: 'Subjects', icon: 'lucide:book', route: 'subjects.index' },
    ],
  },
 
  {
    label: null,
    class: 'mt-auto',
    items: [
      {
        name: 'Support',
        icon: 'lucide:life-buoy',
        href: 'https://github.com/yukazakiri/DccpHubv2/issues',
        external: true,
      },
      {
        name: 'Change-Log',
        icon: 'lucide:file-text',
        route: 'changelog.index',
        external: false,
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
