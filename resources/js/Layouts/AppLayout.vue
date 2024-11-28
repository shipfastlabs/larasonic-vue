<script setup>
import ApplicationMark from '@/Components/ApplicationMark.vue'
import NavLink from '@/Components/NavLink.vue'
import { Avatar, AvatarFallback } from '@/Components/shadcn/ui/avatar'
import AvatarImage from '@/Components/shadcn/ui/avatar/AvatarImage.vue'
import { Button } from '@/Components/shadcn/ui/button'
import {
  Command,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandSeparator,
} from '@/Components/shadcn/ui/command'
import CommandEmpty from '@/Components/shadcn/ui/command/CommandEmpty.vue'
import CommandList from '@/Components/shadcn/ui/command/CommandList.vue'
import CommandShortcut from '@/Components/shadcn/ui/command/CommandShortcut.vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/Components/shadcn/ui/dialog'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/shadcn/ui/dropdown-menu'
import DropdownMenuGroup from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuGroup.vue'
import DropdownMenuShortcut from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuShortcut.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'
import Label from '@/Components/shadcn/ui/label/Label.vue'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/shadcn/ui/popover'
import { Icon } from '@iconify/vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { useColorMode, useMagicKeys } from '@vueuse/core'
import { inject, ref, watch } from 'vue'

defineProps({
  title: String,
})
const route = inject('route')
const mode = useColorMode()

const open = ref(false)

function switchToTeam(team) {
  router.put(route('current-team.update'), {
    team_id: team.id,
  }, {
    preserveState: false,
  })
}

function logout() {
  router.post(route('logout'))
}

const showNewTeamDialog = ref(false)

const createTeamForm = useForm({
  name: '',
})

function createTeam() {
  createTeamForm.post(route('teams.store'), {
    errorBag: 'createTeam',
    preserveScroll: true,
    onSuccess: () => showNewTeamDialog.value = false,
  })
}

const keys = useMagicKeys()
const shiftCtrlL = keys['Shift+Cmd+L']

watch(shiftCtrlL, (v) => {
  if (v) {
    logout()
  }
})
</script>

<template>
  <div>
    <Head :title="title" />
    <div class="min-h-screen">
      <nav class="border-b">
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <div class="flex">
              <!-- Logo -->
              <div class="flex shrink-0 items-center">
                <NavLink :href="route('dashboard')">
                  <ApplicationMark class="block h-9 w-auto" />
                </NavLink>
              </div>

              <!-- Navigation Links -->
              <div class="flex space-x-8 sm:-my-px sm:ms-10">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                  Dashboard
                </NavLink>
              </div>
            </div>

            <div class="flex sm:items-center">
              <!-- Theme Toggle -->
              <div class="relative ms-3">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="relative rounded-full">
                      <Icon
                        icon="lucide:moon"
                        class="rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"
                      />
                      <Icon
                        icon="lucide:sun"
                        class="absolute size-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"
                      />
                      <span class="sr-only">Toggle theme</span>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="mode = 'light'">
                      Light
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="mode = 'dark'">
                      Dark
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="mode = 'auto'">
                      System
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </div>

              <!-- Teams Dropdown -->
              <div class="relative ms-3">
                <!-- Teams Dropdown -->
                <Dialog v-model:open="showNewTeamDialog">
                  <Popover v-if="$page.props.jetstream.hasTeamFeatures" v-model:open="open">
                    <PopoverTrigger as-child>
                      <Button
                        variant="outline" role="combobox" aria-expanded="open"
                        class="justify-between"
                      >
                        Manage Team
                        <Icon
                          icon="lucide:chevrons-up-down"
                          class="ml-auto size-4 shrink-0 opacity-50"
                        />
                      </Button>
                    </PopoverTrigger>
                    <PopoverContent class="p-0">
                      <Command
                        :filter-function="(list, term) => list.filter(i => i?.name?.toLowerCase()?.includes(term))"
                      >
                        <CommandList>
                          <CommandInput placeholder="Search team..." />
                          <CommandEmpty>No team found.</CommandEmpty>
                          <CommandGroup
                            v-if="$page.props.auth.user.all_teams.length > 1"
                            heading="Switch Teams"
                          >
                            <CommandItem
                              v-for="team in $page.props.auth.user.all_teams"
                              :key="team.value" :value="team" @select="() => {
                                switchToTeam(team);
                                open = false;
                              }"
                            >
                              <Avatar class="mr-2 size-5">
                                <AvatarFallback>
                                  {{ team.name.charAt(0) }}
                                </AvatarFallback>
                              </Avatar>
                              {{ team.name }}
                              <Icon
                                v-if="team.id === $page.props.auth.user.current_team_id"
                                icon="lucide:check"
                                class="ml-auto size-4"
                              />
                            </CommandItem>
                          </CommandGroup>
                        </CommandList>
                        <CommandSeparator v-if="$page.props.auth.user.all_teams.length > 1" />
                        <CommandGroup heading="Manage Team">
                          <CommandItem value="team-settings">
                            <Link
                              :href="route('teams.show', $page.props.auth.user.current_team)"
                            >
                              Team Settings
                            </Link>
                            <CommandShortcut>⌘S</CommandShortcut>
                          </CommandItem>
                          <DialogTrigger as-child>
                            <CommandItem
                              v-if="$page.props.jetstream.canCreateTeams"
                              value="create-new-team" @click="showNewTeamDialog = true"
                            >
                              <Link :href="route('teams.create')">
                                Create New Team
                              </Link>
                              <CommandShortcut>⌘T</CommandShortcut>
                            </CommandItem>
                          </DialogTrigger>
                        </CommandGroup>
                      </Command>
                    </PopoverContent>
                  </Popover>
                  <DialogContent>
                    <DialogHeader>
                      <DialogTitle>Create team</DialogTitle>
                      <DialogDescription>
                        Create a new team to collaborate with your team members.
                      </DialogDescription>
                    </DialogHeader>
                    <div>
                      <div class="space-y-4 py-2 pb-4">
                        <div class="space-y-2">
                          <Label for="name">Team name</Label>
                          <Input
                            id="name" v-model="createTeamForm.name"
                            placeholder="Acme Inc."
                          />
                        </div>
                      </div>
                    </div>
                    <DialogFooter>
                      <Button variant="outline" @click="showNewTeamDialog = false">
                        Cancel
                      </Button>
                      <Button
                        type="submit" :disabled="createTeamForm.processing"
                        @click="createTeam"
                      >
                        Continue
                      </Button>
                    </DialogFooter>
                  </DialogContent>
                </Dialog>
              </div>

              <!-- Settings Dropdown -->
              <div class="relative ms-3">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" class="relative size-8 rounded-full">
                      <Avatar class="size-8">
                        <AvatarImage
                          :src="$page.props?.auth?.user?.profile_photo_url ?? ''"
                          :alt="$page.props?.auth?.user?.name"
                        />
                        <AvatarFallback>
                          {{ $page.props?.auth?.user?.name?.charAt(0) }}
                        </AvatarFallback>
                      </Avatar>
                    </Button>
                  </DropdownMenuTrigger>

                  <DropdownMenuContent class="w-56">
                    <DropdownMenuLabel class="flex font-normal">
                      <div class="flex flex-col space-y-1">
                        <p class="text-sm font-medium leading-none">
                          {{ $page.props.auth.user.name }}
                        </p>
                        <p class="text-xs leading-none text-muted-foreground">
                          {{ $page.props.auth.user.email }}
                        </p>
                      </div>
                    </DropdownMenuLabel>

                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                      <DropdownMenuLabel>
                        Manage Account
                      </DropdownMenuLabel>

                      <DropdownMenuItem>
                        <Link :href="route('profile.show')">
                          Profile
                        </Link>
                      </DropdownMenuItem>

                      <DropdownMenuItem v-if="$page.props.jetstream.hasApiFeatures">
                        <Link :href="route('api-tokens.index')">
                          API Tokens
                        </Link>
                      </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="logout">
                      Log out
                      <DropdownMenuShortcut>⇧⌘l</DropdownMenuShortcut>
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </div>
            </div>
          </div>
        </div>
      </nav>
      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
