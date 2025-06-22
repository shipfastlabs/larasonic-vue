import { inject } from 'vue'

<script setup>
import { useForm } from '@inertiajs/vue3'
import { inject } from 'vue'
import InputError from '@/components/InputError.vue'
import AuthenticationCardLogo from '@/components/LogoRedirect.vue'
import Button from '@/components/ui/button/Button.vue'

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags.js'
import { cn } from '@/lib/utils'

const props = defineProps({
  email: String,
  token: String,
})

useSeoMetaTags({
  title: 'Register',
})

const route = inject('route')
const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center">
    <Card class="mx-auto max-w-lg" :class="cn('w-[380px]')">
      <CardHeader>
        <CardTitle class="flex justify-center">
          <AuthenticationCardLogo />
        </CardTitle>
        <CardDescription class="text-center text-2xl">
          Set your new password
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit">
          <div>
            <Label for="email">Email</Label>
            <Input
              id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4">
            <Label for="password">Password</Label>
            <Input
              id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
              autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password" />
          </div>

          <div class="mt-4">
            <Label for="password_confirmation">Confirm Password</Label>
            <Input
              id="password_confirmation" v-model="form.password_confirmation" type="password"
              class="mt-1 block w-full" required autocomplete="new-password"
            />
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
          </div>

          <div class="mt-4 flex items-center justify-end">
            <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Reset Password
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
