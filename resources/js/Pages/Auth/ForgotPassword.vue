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

defineProps({
  status: String,
})

useSeoMetaTags({
  title: 'Forgot Password',
})

const route = inject('route')
const form = useForm({
  email: '',
})

function submit() {
  form.post(route('password.email'))
}
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center">
    <Card class="mx-auto max-w-lg">
      <CardHeader>
        <CardTitle class="flex justify-center">
          <AuthenticationCardLogo />
        </CardTitle>
        <CardDescription class="text-center text-2xl">
          Reset your password
        </CardDescription>
      </CardHeader>

      <CardContent>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
          Forgot your password? No problem. Just let us know your email address and we will email you a
          password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
          {{ status }}
        </div>

        <form @submit.prevent="submit">
          <div>
            <Label for="email">Email</Label>
            <Input
              id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus
              autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4 flex items-center justify-end">
            <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Email Password Reset Link
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
