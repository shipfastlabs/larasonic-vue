<script setup>
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'

import Label from '@/Components/shadcn/ui/label/Label.vue'
import { useForm } from '@inertiajs/vue3'
import { inject } from 'vue'

const props = defineProps({
  team: Object,
  permissions: Object,
})
const route = inject('route')
const form = useForm({
  name: props.team.name,
})

function updateTeamName() {
  form.put(route('teams.update', props.team), {
    errorBag: 'updateTeamName',
    preserveScroll: true,
  })
}
</script>

<template>
  <FormSection @submitted="updateTeamName">
    <template #title>
      Team Name
    </template>

    <template #description>
      The team's name and owner information.
    </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="col-span-6">
        <Label value="Team Owner" />

        <div class="mt-2 flex items-center">
          <Avatar>
            <AvatarImage :src="team.owner.profile_photo_url" alt="profile photo" />
            <AvatarFallback class="rounded-full bg-secondary p-2">
              {{ team.name.charAt(0) }}
            </AvatarFallback>
          </Avatar>

          <div class="ms-4 leading-tight">
            <div class="text-gray-900 dark:text-white">
              {{ team.owner.name }}
            </div>
            <div class="text-sm text-gray-700 dark:text-gray-300">
              {{ team.owner.email }}
            </div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4 ">
        <Label for="name">Team Name</Label>

        <Input
          id="name" v-model="form.name" type="text" class="mt-1 block w-full"
          :disabled="!permissions.canUpdateTeam"
        />

        <InputError :message="form.errors.name" class="mt-2" />
      </div>
    </template>

    <template v-if="permissions.canUpdateTeam" #actions>
      <ActionMessage :on="form.recentlySuccessful" class="me-3">
        Saved.
      </ActionMessage>

      <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </Button>
    </template>
  </FormSection>
</template>
