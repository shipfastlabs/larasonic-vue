<script setup>
import { router, useForm, usePage } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import { toast } from 'vue-sonner'
import ActionSection from '@/components/ActionSection.vue'
import FormSection from '@/components/FormSection.vue'
import InputError from '@/components/InputError.vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'

import Button from '@/components/ui/button/Button.vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Separator from '@/components/ui/separator/Separator.vue'

const props = defineProps({
  team: Object,
  availableRoles: Array,
  userPermissions: Object,
})

const route = inject('route')

const page = usePage()

const addTeamMemberForm = useForm({
  email: '',
  role: null,
})

const updateRoleForm = useForm({
  role: null,
})

const leaveTeamForm = useForm({})
const removeTeamMemberForm = useForm({})

const currentlyManagingRole = ref(false)
const managingRoleFor = ref(null)
const confirmingLeavingTeam = ref(false)
const teamMemberBeingRemoved = ref(null)

function addTeamMember() {
  addTeamMemberForm.post(route('team-members.store', props.team), {
    errorBag: 'addTeamMember',
    preserveScroll: true,
    onSuccess: () => {
      addTeamMemberForm.reset()
      toast.success('Team member added')
    },
  })
}

function cancelTeamInvitation(invitation) {
  router.delete(route('team-invitations.destroy', invitation), {
    preserveScroll: true,
    onSuccess: () => toast.success('Team invitation canceled'),
  })
}

function manageRole(teamMember) {
  managingRoleFor.value = teamMember
  updateRoleForm.role = teamMember.membership.role
  currentlyManagingRole.value = true
}

function updateRole() {
  updateRoleForm.put(route('team-members.update', [props.team, managingRoleFor.value]), {
    preserveScroll: true,
    onSuccess: () => {
      currentlyManagingRole.value = false
      toast.success('Team member role updated')
    },
  })
}

function confirmLeavingTeam() {
  confirmingLeavingTeam.value = true
}

function leaveTeam() {
  leaveTeamForm.delete(route('team-members.destroy', [props.team, page.props.auth.user]), {
    onSuccess: () => {
      confirmingLeavingTeam.value = false
      toast.success('Team left successfully')
    },
  })
}

function confirmTeamMemberRemoval(teamMember) {
  teamMemberBeingRemoved.value = teamMember
}

function removeTeamMember() {
  removeTeamMemberForm.delete(route('team-members.destroy', [props.team, teamMemberBeingRemoved.value]), {
    errorBag: 'removeTeamMember',
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      teamMemberBeingRemoved.value = null
      toast.success('Team member removed successfully')
    },
  })
}

function displayableRole(role) {
  return props.availableRoles.find(r => r.key === role).name
}
</script>

<template>
  <div>
    <div v-if="userPermissions.canAddTeamMembers">
      <Separator class="my-8 hidden sm:block" />

      <!-- Add Team Member -->
      <FormSection @submitted="addTeamMember">
        <template #title>
          Add Team Member
        </template>

        <template #description>
          Add a new team member to your team, allowing them to collaborate with you.
        </template>

        <template #form>
          <div class="col-span-6">
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
              Please provide the email address of the person you would like to add to this team.
            </div>
          </div>

          <!-- Member Email -->
          <div class="col-span-6 sm:col-span-4">
            <Label for="email">Email</Label>
            <Input id="email" v-model="addTeamMemberForm.email" type="email" class="mt-1 block w-full" />
            <InputError :message="addTeamMemberForm.errors.email" class="mt-2" />
          </div>

          <!-- Role -->
          <div v-if="availableRoles.length > 0" class="col-span-6 lg:col-span-4">
            <Label for="roles">Role</Label>

            <div class="relative z-0 mt-1 cursor-pointer rounded-lg border">
              <button
                v-for="(role, i) in availableRoles" :key="role.key" type="button"
                class="relative inline-flex w-full rounded-lg px-4 py-3 focus:z-10 focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary dark:focus:border-primary dark:focus:ring-primary"
                :class="{ 'rounded-t-none border-t focus:border-none ': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1 }"
                @click="addTeamMemberForm.role = role.key"
              >
                <div
                  :class="{ 'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role !== role.key }"
                >
                  <!-- Role Name -->
                  <div class="flex items-center">
                    <div
                      class="text-sm text-primary"
                      :class="{ 'font-semibold': addTeamMemberForm.role === role.key }"
                    >
                      {{ role.name }}
                    </div>

                    <svg
                      v-if="addTeamMemberForm.role === role.key"
                      class="ms-2 size-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                      fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                  </div>

                  <!-- Role Description -->
                  <div class="mt-2 text-start text-xs">
                    {{ role.description }}
                  </div>
                </div>
              </button>
            </div>

            <InputError :message="addTeamMemberForm.errors.role" class="mt-2" />
          </div>
        </template>

        <template #actions>
          <Button
            :class="{ 'opacity-25': addTeamMemberForm.processing }"
            :disabled="addTeamMemberForm.processing"
          >
            Add
          </Button>
        </template>
      </FormSection>
    </div>

    <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
      <Separator class="my-8 hidden sm:block" />

      <!-- Team Member Invitations -->
      <ActionSection class="mt-10 sm:mt-0">
        <template #title>
          Pending Team Invitations
        </template>

        <template #description>
          These people have been invited to your team and have been sent an invitation email. They may join
          the team by accepting the email invitation.
        </template>

        <!-- Pending Team Member Invitation List -->
        <template #content>
          <div class="space-y-6">
            <div
              v-for="invitation in team.team_invitations" :key="invitation.id"
              class="flex items-center justify-between"
            >
              <div class="text-gray-600 dark:text-gray-400">
                {{ invitation.email }}
              </div>

              <div class="flex items-center">
                <!-- Cancel Team Invitation -->
                <button
                  v-if="userPermissions.canRemoveTeamMembers"
                  class="ms-6 cursor-pointer text-sm text-red-500 focus:outline-hidden"
                  @click="cancelTeamInvitation(invitation)"
                >
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </template>
      </ActionSection>
    </div>

    <div v-if="team.users.length > 0">
      <Separator class="my-8 hidden sm:block" />

      <!-- Manage Team Members -->
      <ActionSection class="mt-10 sm:mt-0">
        <template #title>
          Team Members
        </template>

        <template #description>
          All of the people that are part of this team.
        </template>

        <!-- Team Member List -->
        <template #content>
          <div class="space-y-6">
            <div v-for="user in team.users" :key="user.id" class="flex items-center justify-between">
              <div class="flex items-center">
                <Avatar size="sm" shape="circle">
                  <AvatarImage :src="user.profile_photo_url ?? ''" :alt="user.name" />
                  <AvatarFallback>{{ user.name.charAt(0) }}</AvatarFallback>
                </Avatar>
                <div class="ms-4">
                  {{ user.name }}
                </div>
              </div>

              <div class="flex items-center">
                <!-- Manage Team Member Role -->
                <button
                  v-if="userPermissions.canUpdateTeamMembers && availableRoles.length"
                  class="ms-2 text-sm underline" @click="manageRole(user)"
                >
                  {{ displayableRole(user.membership.role) }}
                </button>

                <div v-else-if="availableRoles.length" class="ms-2 text-sm">
                  {{ displayableRole(user.membership.role) }}
                </div>

                <!-- Leave Team -->
                <button
                  v-if="$page.props.auth.user.id === user.id"
                  class="ms-6 cursor-pointer text-sm text-red-500" @click="confirmLeavingTeam"
                >
                  Leave
                </button>

                <!-- Remove Team Member -->
                <button
                  v-else-if="userPermissions.canRemoveTeamMembers"
                  class="ms-6 cursor-pointer text-sm text-red-500"
                  @click="confirmTeamMemberRemoval(user)"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </template>
      </ActionSection>
    </div>

    <!-- Role Management Modal -->
    <Dialog :open="currentlyManagingRole" @update:open="currentlyManagingRole = false">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Manage Role</DialogTitle>
          <DialogDescription>
            Manage the role of this team member.
          </DialogDescription>
        </DialogHeader>

        <div v-if="managingRoleFor">
          <div class="relative z-0 mt-1 cursor-pointer rounded-lg border border-primary">
            <button
              v-for="(role, i) in availableRoles" :key="role.key" type="button"
              class="relative inline-flex w-full rounded-lg px-4 py-3 focus:z-10 focus:border-primary focus:outline-hidden focus:ring-2 focus:ring-primary"
              :class="{ 'rounded-t-none border-t focus:border-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1 }"
              @click="updateRoleForm.role = role.key"
            >
              <div :class="{ 'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.key }">
                <!-- Role Name -->
                <div class="flex items-center">
                  <div class="text-sm" :class="{ 'font-semibold': updateRoleForm.role === role.key }">
                    {{ role.name }}
                  </div>

                  <svg
                    v-if="updateRoleForm.role === role.key" class="ms-2 size-5 text-green-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>

                <!-- Role Description -->
                <div class="mt-2 text-xs">
                  {{ role.description }}
                </div>
              </div>
            </button>
          </div>
        </div>

        <DialogFooter>
          <Button variant="secondary" @click="currentlyManagingRole = false">
            Cancel
          </Button>

          <Button
            class="ms-3" :class="{ 'opacity-25': updateRoleForm.processing }"
            :disabled="updateRoleForm.processing" @click="updateRole"
          >
            Save
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Leave Team Confirmation Modal -->
    <Dialog :open="confirmingLeavingTeam" @update:open="confirmingLeavingTeam = false">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Leave Team</DialogTitle>
          <DialogDescription>
            Are you sure you would like to leave this team?
          </DialogDescription>
        </DialogHeader>

        <DialogFooter>
          <Button variant="secondary" @click="confirmingLeavingTeam = false">
            Cancel
          </Button>

          <Button
            variant="destructive" class="ms-3" :class="{ 'opacity-25': leaveTeamForm.processing }"
            :disabled="leaveTeamForm.processing" @click="leaveTeam"
          >
            Leave
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Remove Team Member Confirmation Modal -->
    <Dialog :open="!!teamMemberBeingRemoved" @update:open="teamMemberBeingRemoved = null">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Remove Team Member</DialogTitle>
          <DialogDescription>
            Are you sure you would like to remove this person from the team?
          </DialogDescription>
        </DialogHeader>

        <DialogFooter>
          <Button variant="secondary" @click="teamMemberBeingRemoved = null">
            Cancel
          </Button>

          <Button
            variant="destructive" class="ms-3"
            :class="{ 'opacity-25': removeTeamMemberForm.processing }"
            :disabled="removeTeamMemberForm.processing" @click="removeTeamMember"
          >
            Remove
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>
