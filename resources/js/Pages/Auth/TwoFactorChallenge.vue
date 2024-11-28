<script setup>
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'
import Label from '@/Components/shadcn/ui/label/Label.vue'

import { Head, useForm } from '@inertiajs/vue3'
import { inject, nextTick, ref } from 'vue'

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
  <Head title="Two-factor Confirmation" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      <template v-if="!recovery">
        Please confirm access to your account by entering the authentication code provided by your authenticator application.
      </template>

      <template v-else>
        Please confirm access to your account by entering one of your emergency recovery codes.
      </template>
    </div>

    <form @submit.prevent="submit">
      <div v-if="!recovery">
        <Label for="code">Code</Label>
        <Input
          id="code"
          ref="codeInput"
          v-model="form.code"
          type="text"
          inputmode="numeric"
          class="mt-1 block w-full"
          autofocus
          autocomplete="one-time-code"
        />
        <InputError class="mt-2" :message="form.errors.code" />
      </div>

      <div v-else>
        <Label for="recovery_code">Recovery Code</Label>
        <Input
          id="recovery_code"
          ref="recoveryCodeInput"
          v-model="form.recovery_code"
          type="text"
          class="mt-1 block w-full"
          autocomplete="one-time-code"
        />
        <InputError class="mt-2" :message="form.errors.recovery_code" />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <button type="button" class="cursor-pointer text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-400" @click.prevent="toggleRecovery">
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
  </AuthenticationCard>
</template>
