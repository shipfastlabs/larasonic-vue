<script setup>
import { Icon } from '@iconify/vue'
import { Link, router } from '@inertiajs/vue3'
import { inject } from 'vue'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import AvatarImage from '@/components/ui/avatar/AvatarImage.vue'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import DropdownMenuGroup from '@/components/ui/dropdown-menu/DropdownMenuGroup.vue'
import SidebarMenuButton from '@/components/ui/sidebar/SidebarMenuButton.vue'

const route = inject('route')

function logout() {
  router.post(route('logout'))
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <SidebarMenuButton
        size="lg"
        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
      >
        <Avatar class="h-8 w-8 rounded-lg">
          <AvatarImage
            :src="$page.props.auth.user.profile_photo_path ?? ''"
            :alt="$page.props.auth.user.name"
          />
          <AvatarFallback class="rounded-lg">
            {{ $page.props.auth.user.name.charAt(0) }}
          </AvatarFallback>
        </Avatar>
        <div class="grid flex-1 text-left text-sm leading-tight">
          <span class="truncate font-semibold">{{ $page.props.auth.user.name }}</span>
          <span class="truncate text-xs">{{ $page.props.auth.user.email }}</span>
        </div>
        <Icon icon="lucide:chevrons-up-down" class="ml-auto size-4" />
      </SidebarMenuButton>
    </DropdownMenuTrigger>
    <DropdownMenuContent
      class="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
      side="bottom" align="end" :side-offset="4"
    >
      <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
          <Avatar class="h-8 w-8 rounded-lg">
            <AvatarImage
              :src="$page.props.auth.user.profile_photo_path ?? ''"
              :alt="$page.props.auth.user.name"
            />
            <AvatarFallback class="rounded-lg">
              {{ $page.props.auth.user.name.charAt(0) }}
            </AvatarFallback>
          </Avatar>
          <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-semibold">{{ $page.props.auth.user.name }}</span>
            <span class="truncate text-xs">{{ $page.props.auth.user.email }}</span>
          </div>
        </div>
      </DropdownMenuLabel>
      <DropdownMenuSeparator />
      <DropdownMenuGroup>
        <DropdownMenuItem :as="Link" :href="route('profile.show')">
          <Icon icon="lucide:settings" />
          Settings
        </DropdownMenuItem>
        <DropdownMenuItem :as="Link" :href="route('api-tokens.index')">
          <Icon icon="lucide:key" />
          API Tokens
        </DropdownMenuItem>
        <DropdownMenuItem :as="Link" :href="route('subscriptions.create')">
          <Icon icon="lucide:credit-card" />
          Billing
        </DropdownMenuItem>
      </DropdownMenuGroup>
      <DropdownMenuSeparator />
      <DropdownMenuItem @click="logout">
        <Icon icon="lucide:log-out" />
        Log out
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
