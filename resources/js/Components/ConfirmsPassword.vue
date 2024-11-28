<script setup>
import Input from '@/Components/shadcn/ui/input/Input.vue'
import axios from 'axios'
import { inject, nextTick, reactive, ref } from 'vue'
import DialogModal from './DialogModal.vue'
import InputError from './InputError.vue'
import Button from './shadcn/ui/button/Button.vue'

defineProps({
  title: {
    type: String,
    default: 'Confirm Password',
  },
  content: {
    type: String,
    default: 'For your security, please confirm your password to continue.',
  },
  button: {
    type: String,
    default: 'Confirm',
  },
})
const emit = defineEmits(['confirmed'])
const route = inject('route')
const confirmingPassword = ref(false)

const form = reactive({
  password: '',
  error: '',
  processing: false,
})

const passwordInput = ref(null)

function startConfirmingPassword() {
  axios.get(route('password.confirmation')).then((response) => {
    if (response.data.confirmed) {
      emit('confirmed')
    }
    else {
      confirmingPassword.value = true

      setTimeout(() => passwordInput.value.focus(), 250)
    }
  })
}

function confirmPassword() {
  form.processing = true

  axios.post(route('password.confirm'), {
    password: form.password,
  }).then(() => {
    form.processing = false

    closeModal()
    nextTick().then(() => emit('confirmed'))
  }).catch((error) => {
    form.processing = false
    form.error = error.response.data.errors.password[0]
    passwordInput.value.focus()
  })
}

function closeModal() {
  confirmingPassword.value = false
  form.password = ''
  form.error = ''
}
</script>

<template>
  <span>
    <span @click="startConfirmingPassword">
      <slot />
    </span>

    <DialogModal :show="confirmingPassword" @close="closeModal">
      <template #title>
        {{ title }}
      </template>

      <template #content>
        {{ content }}

        <div class="mt-4">
          <Input
            ref="passwordInput" v-model="form.password" type="password" class="mt-1 block w-3/4"
            placeholder="Password" autocomplete="current-password" @keyup.enter="confirmPassword"
          />

          <InputError :message="form.error" class="mt-2" />
        </div>
      </template>

      <template #footer>
        <Button variant="secondary" @click="closeModal">
          Cancel
        </Button>

        <Button
          class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
          @click="confirmPassword"
        >
          {{ button }}
        </Button>
      </template>
    </DialogModal>
  </span>
</template>
