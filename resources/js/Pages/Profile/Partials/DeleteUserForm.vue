<script setup>
import { useForm } from '@inertiajs/vue3'
import { inject } from 'vue'
import ActionSection from '@/components/ActionSection.vue'
import ConfirmsPassword from '@/components/ConfirmsPassword.vue'
import Button from '@/components/ui/button/Button.vue'

const route = inject('route')
const form = useForm({})

function deleteUser(password) {
  form.transform(data => ({
    ...data,
    password,
  })).delete(route('current-user.destroy'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onFinish: () => form.reset(),
  })
}
</script>

<template>
  <ActionSection>
    <template #title>
      Delete Account
    </template>

    <template #description>
      Permanently delete your account.
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
      </div>

      <div class="mt-5">
        <ConfirmsPassword
          title="Delete Account"
          content="Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm."
          button="Delete Account"
          @confirmed="deleteUser"
        >
          <Button variant="destructive">
            Delete Account
          </Button>
        </ConfirmsPassword>
      </div>
    </template>
  </ActionSection>
</template>
