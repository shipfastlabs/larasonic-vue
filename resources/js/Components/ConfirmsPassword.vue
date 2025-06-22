<script setup>
import axios from 'axios'
import { inject, reactive, ref } from 'vue'
import InputError from '@/components/InputError.vue'
import Button from '@/components/ui/button/Button.vue'
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog'
import Input from '@/components/ui/input/Input.vue'

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
function confirmPassword() {
  form.processing = true

  axios.post(route('password.confirm'), {
    password: form.password,
  }).then(() => {
    form.processing = false
    emit('confirmed', form.password)
    closeModal()
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
    <Dialog v-model:open="confirmingPassword">
      <DialogTrigger>
        <slot />
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{{ title }}</DialogTitle>
          <DialogDescription>
            {{ content }}
          </DialogDescription>
        </DialogHeader>

        <div class="mt-4">
          <Input
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block"
            placeholder="Password"
            autocomplete="current-password"
            @keyup.enter="confirmPassword"
          />

          <InputError :message="form.error" class="mt-2" />
        </div>

        <DialogFooter class="mt-4">
          <DialogClose as-child>
            <Button variant="secondary" @click="closeModal">
              Cancel
            </Button>
          </DialogClose>

          <Button
            variant="destructive"
            class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
            @click="confirmPassword"
          >
            {{ button }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </span>
</template>
