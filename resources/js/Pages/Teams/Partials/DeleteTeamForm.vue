<script setup>
import { useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import ActionSection from '@/components/ActionSection.vue'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
import Button from '@/components/ui/button/Button.vue'

const props = defineProps({
  team: Object,
})
const route = inject('route')
const confirmingTeamDeletion = ref(false)
const form = useForm({})

function confirmTeamDeletion() {
  confirmingTeamDeletion.value = true
}

function deleteTeam() {
  form.delete(route('teams.destroy', props.team), {
    errorBag: 'deleteTeam',
  })
}
</script>

<template>
  <ActionSection>
    <template #title>
      Delete Team
    </template>

    <template #description>
      Permanently delete this team.
    </template>

    <template #content>
      <div class="max-w-xl text-sm">
        Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.
      </div>

      <div class="mt-5">
        <Button
          variant="destructive"
          @click="confirmTeamDeletion"
        >
          Delete Team
        </Button>
      </div>

      <!-- Delete Team Confirmation Modal -->
      <ConfirmationModal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
        <template #title>
          Delete Team
        </template>

        <template #content>
          Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.
        </template>

        <template #footer>
          <Button
            variant="secondary"
            @click="confirmingTeamDeletion = false"
          >
            Cancel
          </Button>

          <Button
            variant="destructive"
            class="ms-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteTeam"
          >
            Delete Team
          </Button>
        </template>
      </ConfirmationModal>
    </template>
  </ActionSection>
</template>
