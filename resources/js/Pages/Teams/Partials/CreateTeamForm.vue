<script setup>
import { Button } from '@/Components/shadcn/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/Components/shadcn/ui/dialog'
import { Input } from '@/Components/shadcn/ui/input'
import { Label } from '@/Components/shadcn/ui/label'
import { useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'

const route = inject('route')
const showNewTeamDialog = ref(false)
const form = useForm({
  name: '',
  plan: 'free',
})

function createTeam() {
  form.post(route('teams.store'), {
    errorBag: 'createTeam',
    preserveScroll: true,
    onSuccess: () => showNewTeamDialog.value = false,
  })
}
</script>

<template>
  <Dialog v-model:open="showNewTeamDialog">
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
              id="name"
              v-model="form.name"
              placeholder="Acme Inc."
            />
          </div>
        </div>
      </div>
      <DialogFooter>
        <Button variant="outline" @click="showNewTeamDialog = false">
          Cancel
        </Button>
        <Button type="submit" :disabled="form.processing" @click="createTeam">
          Continue
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
