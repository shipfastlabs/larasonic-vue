<script setup>
import FormSection from '@/componentsFormSection.vue'
import InputError from '@/componentsInputError.vue'
import Avatar from '@/componentsui/avatar/Avatar.vue'
import AvatarFallback from '@/componentsui/avatar/AvatarFallback.vue'
import AvatarImage from '@/componentsui/avatar/AvatarImage.vue'
import Button from '@/componentsui/button/Button.vue'
import Input from '@/componentsui/input/Input.vue'

import Label from '@/componentsui/label/Label.vue'
import { useForm } from '@inertiajs/vue3'
import { inject } from 'vue'
import { toast } from 'vue-sonner'

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
    onSuccess: () => toast.success('Team name updated successfully'),
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
            <AvatarImage :src="team.owner.profile_photo_path ?? ''" alt="profile photo" />
            <AvatarFallback class="rounded-full bg-secondary p-2">
              {{ team.name.charAt(0) }}
            </AvatarFallback>
          </Avatar>

          <div class="ms-4 leading-tight">
            <div class="">
              {{ team.owner.name }}
            </div>
            <div class="text-sm ">
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
      <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </Button>
    </template>
  </FormSection>
</template>
