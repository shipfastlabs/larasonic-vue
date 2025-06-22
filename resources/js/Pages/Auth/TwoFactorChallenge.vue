<script setup>
import { useForm } from '@inertiajs/vue3'
import { inject, nextTick, ref } from 'vue'
import InputError from '@/components/InputError.vue'
import AuthenticationCardLogo from '@/components/LogoRedirect.vue'
import Button from '@/components/ui/button/Button.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import Input from '@/components/ui/input/Input.vue'

import Label from '@/components/ui/label/Label.vue'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags.js'

useSeoMetaTags({
  title: 'Two-factor Confirmation',
})

const route = inject('route')
const recovery = ref(false)

const form = useForm({
  code: '',
  recovery_code: '',
})

const recoveryCodeInput = ref(null)
const codeInput = ref(null)

async function toggleRecovery() {
  recovery.value ^= true

  await nextTick()

  if (recovery.value) {
    recoveryCodeInput.value.focus()
    form.code = ''
  }
  else {
    codeInput.value.focus()
    form.recovery_code = ''
  }
}

function submit() {
  form.post(route('two-factor.login'))
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
          Two-factor authentication
        </CardDescription>
      </CardHeader>

      <CardContent>
        <div class="mb-4 text-sm">
          <template v-if="!recovery">
            Please confirm access to your account by entering the authentication code provided by your
            authenticator application.
          </template>

          <template v-else>
            Please confirm access to your account by entering one of your emergency recovery codes.
          </template>
        </div>

        <form @submit.prevent="submit">
          <div v-if="!recovery">
            <Label for="code">Code</Label>
            <Input
              id="code" ref="codeInput" v-model="form.code" type="text" inputmode="numeric"
              class="mt-1 block w-full" autofocus autocomplete="one-time-code"
            />
            <InputError class="mt-2" :message="form.errors.code" />
          </div>

          <div v-else>
            <Label for="recovery_code">Recovery Code</Label>
            <Input
              id="recovery_code" ref="recoveryCodeInput" v-model="form.recovery_code" type="text"
              class="mt-1 block w-full" autocomplete="one-time-code"
            />
            <InputError class="mt-2" :message="form.errors.recovery_code" />
          </div>

          <div class="mt-4 flex items-center justify-end">
            <button type="button" class="cursor-pointer text-sm" @click.prevent="toggleRecovery">
              <template v-if="!recovery">
                Use a recovery code
              </template>

              <template v-else>
                Use an authentication code
              </template>
            </button>

            <Button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Log in
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  </div>
</template>
