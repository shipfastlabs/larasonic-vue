<script setup>
import Separator from '@/components/ui/separator/Separator.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import DeleteTeamForm from '@/Pages/Teams/Partials/DeleteTeamForm.vue'
import TeamMemberManager from '@/Pages/Teams/Partials/TeamMemberManager.vue'
import UpdateTeamNameForm from '@/Pages/Teams/Partials/UpdateTeamNameForm.vue'

defineProps({
  team: Object,
  availableRoles: Array,
  permissions: Object,
})
</script>

<template>
  <AppLayout title="Team Settings">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Team Settings
      </h2>
    </template>

    <div>
      <div class="max-w-7xl">
        <UpdateTeamNameForm :team="team" :permissions="permissions" />

        <TeamMemberManager
          class="mt-10 sm:mt-0" :team="team" :available-roles="availableRoles"
          :user-permissions="permissions"
        />

        <template v-if="permissions.canDeleteTeam && !team.personal_team">
          <Separator class="my-8 hidden sm:block" />

          <DeleteTeamForm class="mt-10 sm:mt-0" :team="team" />
        </template>
      </div>
    </div>
  </AppLayout>
</template>
