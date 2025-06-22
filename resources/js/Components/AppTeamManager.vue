<script setup>
import { Icon } from '@iconify/vue'
import { Link, router } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import {
  Command,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandSeparator,
} from '@/components/ui/command'
import CommandEmpty from '@/components/ui/command/CommandEmpty.vue'
import CommandList from '@/components/ui/command/CommandList.vue'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import SidebarMenuButton from '@/components/ui/sidebar/SidebarMenuButton.vue'

const route = inject('route')
const open = ref(false)

function switchToTeam(team) {
  router.put(route('current-team.update'), {
    team_id: team.id,
  }, {
    preserveState: false,
  })
}
</script>

<template>
  <DropdownMenu v-model:open="open">
    <DropdownMenuTrigger as-child>
      <SidebarMenuButton
        size="lg"
        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
      >
        <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
          <Icon icon="lucide:rocket" />
        </div>
        <div class="grid flex-1 text-left text-sm leading-tight">
          <span class="truncate font-semibold">{{ $page.props.auth.user.current_team.name }}</span>
          <span class="truncate text-xs">Manage Team</span>
        </div>
        <Icon icon="lucide:chevrons-up-down" class="ml-auto size-4" />
      </SidebarMenuButton>
    </DropdownMenuTrigger>
    <DropdownMenuContent
      class="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg p-0"
      align="start" side="bottom" :side-offset="4"
    >
      <Command :filter-function="(list, term) => list.filter(i => i?.name?.toLowerCase()?.includes(term))">
        <CommandList>
          <CommandInput placeholder="Search team..." />
          <CommandEmpty>No team found.</CommandEmpty>
          <CommandGroup heading="Switch Teams">
            <CommandItem
              v-for="team in $page.props.auth.user.all_teams"
              :key="team.value" :value="team" @select="() => {
                switchToTeam(team);
                open = false;
              }"
            >
              <Avatar class="mr-2 size-5">
                <AvatarFallback>{{ team.name.charAt(0) }}</AvatarFallback>
              </Avatar>
              {{ team.name }}
              <Icon
                v-if="team.id === $page.props.auth.user.current_team_id"
                icon="lucide:check" class="ml-auto size-4"
              />
            </CommandItem>
          </CommandGroup>
        </CommandList>
        <CommandSeparator v-if="$page.props.auth.user.all_teams.length > 1" />
        <CommandGroup heading="Manage Team">
          <CommandItem value="team-settings">
            <Link :href="route('teams.show', $page.props.auth.user.current_team)">
              Team Settings
            </Link>
          </CommandItem>
          <CommandItem
            v-if="$page.props.jetstream.canCreateTeams"
            value="create-new-team"
          >
            <Link :href="route('teams.create')">
              Create New Team
            </Link>
          </CommandItem>
        </CommandGroup>
      </Command>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
