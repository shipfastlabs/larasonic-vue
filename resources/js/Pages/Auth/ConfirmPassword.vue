<script setup>
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'

import Label from '@/Components/shadcn/ui/label/Label.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'

const route = inject('route')
const form = useForm({
  password: '',
})

const passwordInput = ref(null)

function submit() {
  form.post(route('password.confirm'), {
    onFinish: () => {
      form.reset()

      passwordInput.value.focus()
    },
  })
}
</script>

<template>
  <Head title="Secure Area" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      This is a secure area of the application. Please confirm your password before continuing.
    </div>

    <form @submit.prevent="submit">
      <div>
        <Label for="password">Password</Label>
        <Input
          id="password" ref="passwordInput" v-model="form.password" type="password"
          class="mt-1 block w-full" required autocomplete="current-password" autofocus
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 flex justify-end">
        <Button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Confirm
        </Button>
      </div>
    </form>
  </AuthenticationCard>
</template>
