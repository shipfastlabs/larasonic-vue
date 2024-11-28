<script setup>
import ActionSection from '@/Components/ActionSection.vue'
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'
import Label from '@/Components/shadcn/ui/label/Label.vue'

import { router, useForm, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { computed, inject, ref, watch } from 'vue'

const props = defineProps({
  requiresConfirmation: Boolean,
})
const route = inject('route')
const page = usePage()
const enabling = ref(false)
const confirming = ref(false)
const disabling = ref(false)
const qrCode = ref(null)
const setupKey = ref(null)
const recoveryCodes = ref([])

const confirmationForm = useForm({
  code: '',
})

const twoFactorEnabled = computed(
  () => !enabling.value && page.props.auth.user?.two_factor_enabled,
)

watch(twoFactorEnabled, () => {
  if (!twoFactorEnabled.value) {
    confirmationForm.reset()
    confirmationForm.clearErrors()
  }
})

function enableTwoFactorAuthentication() {
  enabling.value = true

  router.post(route('two-factor.enable'), {}, {
    preserveScroll: true,
    onSuccess: () => Promise.all([
      showQrCode(),
      showSetupKey(),
      showRecoveryCodes(),
    ]),
    onFinish: () => {
      enabling.value = false
      confirming.value = props.requiresConfirmation
    },
  })
}

function showQrCode() {
  return axios.get(route('two-factor.qr-code')).then((response) => {
    qrCode.value = response.data.svg
  })
}

function showSetupKey() {
  return axios.get(route('two-factor.secret-key')).then((response) => {
    setupKey.value = response.data.secretKey
  })
}

function showRecoveryCodes() {
  return axios.get(route('two-factor.recovery-codes')).then((response) => {
    recoveryCodes.value = response.data
  })
}

function confirmTwoFactorAuthentication() {
  confirmationForm.post(route('two-factor.confirm'), {
    errorBag: 'confirmTwoFactorAuthentication',
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      confirming.value = false
      qrCode.value = null
      setupKey.value = null
    },
  })
}

function regenerateRecoveryCodes() {
  axios
    .post(route('two-factor.recovery-codes'))
    .then(() => showRecoveryCodes())
}

function disableTwoFactorAuthentication() {
  disabling.value = true

  router.delete(route('two-factor.disable'), {
    preserveScroll: true,
    onSuccess: () => {
      disabling.value = false
      confirming.value = false
    },
  })
}
</script>

<template>
  <ActionSection>
    <template #title>
      Two Factor Authentication
    </template>

    <template #description>
      Add additional security to your account using two factor authentication.
    </template>

    <template #content>
      <h3 v-if="twoFactorEnabled && !confirming" class="text-lg font-medium text-gray-900 dark:text-gray-100">
        You have enabled two factor authentication.
      </h3>

      <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Finish enabling two factor authentication.
      </h3>

      <h3 v-else class="text-lg font-medium text-gray-900 dark:text-gray-100">
        You have not enabled two factor authentication.
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
        <p>
          When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p v-if="confirming" class="font-semibold">
              To finish enabling two factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key and provide the generated OTP code.
            </p>

            <p v-else>
              Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key.
            </p>
          </div>

          <div class="mt-4 inline-block bg-white p-2" v-html="qrCode" />

          <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p class="font-semibold">
              Setup Key: <span v-html="setupKey" />
            </p>
          </div>

          <div v-if="confirming" class="mt-4">
            <Label for="code">Code</Label>

            <Input
              id="code"
              v-model="confirmationForm.code"
              type="text"
              name="code"
              class="mt-1 block w-1/2"
              inputmode="numeric"
              autofocus
              autocomplete="one-time-code"
              @keyup.enter="confirmTwoFactorAuthentication"
            />

            <InputError :message="confirmationForm.errors.code" class="mt-2" />
          </div>
        </div>

        <div v-if="recoveryCodes.length > 0 && !confirming">
          <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
            <p class="font-semibold">
              Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
            </p>
          </div>

          <div class="mt-4 grid max-w-xl gap-1 rounded-lg bg-gray-100 p-4 font-mono text-sm dark:bg-gray-900 dark:text-gray-100">
            <div v-for="code in recoveryCodes" :key="code">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div v-if="!twoFactorEnabled">
          <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
            <Button type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
              Enable
            </Button>
          </ConfirmsPassword>
        </div>

        <div v-else>
          <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
            <Button
              v-if="confirming"
              type="button"
              class="me-3"
              :class="{ 'opacity-25': enabling }"
              :disabled="enabling"
            >
              Confirm
            </Button>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
            <Button
              v-if="recoveryCodes.length > 0 && !confirming"
              variant="secondary"
              class="me-3"
            >
              Regenerate Recovery Codes
            </Button>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="showRecoveryCodes">
            <Button
              v-if="recoveryCodes.length === 0 && !confirming"
              variant="secondary"
              class="me-3"
            >
              Show Recovery Codes
            </Button>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <Button
              v-if="confirming"
              variant="secondary"
              :class="{ 'opacity-25': disabling }"
              :disabled="disabling"
            >
              Cancel
            </Button>
          </ConfirmsPassword>

          <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
            <Button
              v-if="!confirming"
              variant="destructive"
              :class="{ 'opacity-25': disabling }"
              :disabled="disabling"
            >
              Disable
            </Button>
          </ConfirmsPassword>
        </div>
      </div>
    </template>
  </ActionSection>
</template>
