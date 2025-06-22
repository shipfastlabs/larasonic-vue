<script setup>
import FormSection from '@/componentsFormSection.vue'
import InputError from '@/componentsInputError.vue'
import Button from '@/componentsui/button/Button.vue'
import Input from '@/componentsui/input/Input.vue'
import Label from '@/componentsui/label/Label.vue'

import { useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import { toast } from 'vue-sonner'

const route = inject('route')

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

function updatePassword() {
  form.put(route('user-password.update'), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      toast.success('Password updated')
    },
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value.focus()
      }

      if (form.errors.current_password) {
        form.reset('current_password')
        currentPasswordInput.value.focus()
      }
    },
  })
}
</script>

<template>
  <FormSection @submitted="updatePassword">
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <Label for="password">New Password</Label>
        <Input
          id="password" ref="passwordInput" v-model="form.password" type="password"
          class="mt-1 block w-full" autocomplete="new-password"
        />
        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <Label for="password_confirmation">Confirm Password</Label>
        <Input
          id="password_confirmation" v-model="form.password_confirmation" type="password"
          class="mt-1 block w-full" autocomplete="new-password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </Button>
    </template>
  </FormSection>
</template>
