<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { inject } from 'vue'
import InputError from '@/components/InputError.vue'
import AuthenticationCardLogo from '@/components/LogoRedirect.vue'

import Button from '@/components/ui/button/Button.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags.js'

useSeoMetaTags({
  title: 'Register',
})

const route = inject('route')
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

function submit() {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
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
          Create your account
        </CardDescription>
      </CardHeader>

      <CardContent>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div class="grid gap-2">
              <Label for="name">Name</Label>
              <Input id="name" v-model="form.name" type="text" required autofocus autocomplete="name" />
              <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
              <Label for="email">Email</Label>
              <Input id="email" v-model="form.email" type="email" required autocomplete="username" />
              <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
              <Label for="password">Password</Label>
              <Input
                id="password" v-model="form.password" type="password" required
                autocomplete="new-password"
              />
              <InputError :message="form.errors.password" />
            </div>

            <div class="grid gap-2">
              <Label for="password_confirmation">Confirm Password</Label>
              <Input
                id="password_confirmation" v-model="form.password_confirmation" type="password"
                required autocomplete="new-password"
              />
              <InputError :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
              <div class="flex items-center space-x-2">
                <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                <label for="terms" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                  I agree to the
                  <a target="_blank" :href="route('terms.show')" class="rounded-md text-sm underline">Terms of Service</a>
                  and
                  <a target="_blank" :href="route('policy.show')" class="rounded-md text-sm underline">Privacy Policy</a>
                </label>
              </div>
              <InputError :message="form.errors.terms" />
            </div>

            <div class="flex items-center justify-end gap-4">
              <Link :href="route('login')" class="text-sm underline">
                Already registered?
              </Link>

              <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Register
              </Button>
            </div>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
