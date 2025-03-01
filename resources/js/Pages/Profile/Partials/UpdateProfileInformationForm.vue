<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'
import Label from '@/Components/shadcn/ui/label/Label.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import { toast } from 'vue-sonner'

const props = defineProps({
  user: Object,
})
const route = inject('route')
const form = useForm({
  _method: 'PUT',
  name: props.user.name,
  email: props.user.email,
  photo: null,
  phone: props.user.phone,
  role: props.user.role,
  is_active: props.user.is_active,
  person_id: props.user.person_id,
  person_type: props.user.person_type,
})

const verificationLinkSent = ref(null)
const photoPreview = ref(null)
const photoInput = ref(null)

function updateProfileInformation() {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.post(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => {
      clearPhotoFileInput()
      toast.success('Profile information updated')
    },
  })
}

function sendEmailVerification() {
  verificationLinkSent.value = true
}

function selectNewPhoto() {
  photoInput.value.click()
}

function updatePhotoPreview() {
  const photo = photoInput.value.files[0]

  if (!photo)
    return

  const reader = new FileReader()

  reader.onload = (e) => {
    photoPreview.value = e.target.result
  }

  reader.readAsDataURL(photo)
}

function deletePhoto() {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
      toast.success('Photo deleted')
    },
  })
}

function clearPhotoFileInput() {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}
</script>

<template>
  <FormSection @submitted="updateProfileInformation">
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
        <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">
        <Label for="photo">Photo</Label>
        <div v-show="!photoPreview" class="mt-2">
          <img :src="user.profile_photo_url" :alt="user.name" class="size-20 rounded-full object-cover">
        </div>
        <div v-show="photoPreview" class="mt-2">
          <span class="block size-20 rounded-full bg-cover bg-center bg-no-repeat" :style="`background-image: url('${photoPreview}');`" />
        </div>
        <Button variant="secondary" class="me-2 mt-2" type="button" @click.prevent="selectNewPhoto">
          Select A New Photo
        </Button>
        <Button v-if="user.profile_photo_path" variant="secondary" type="button" class="mt-2" @click.prevent="deletePhoto">
          Remove Photo
        </Button>
        <InputError :message="form.errors.photo" class="mt-2" />
      </div>

      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <Label for="name">Name</Label>
        <Input id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autocomplete="name" />
        <InputError :message="form.errors.name" class="mt-2" />
      </div>

      <!-- Email -->
      <div class="col-span-6 sm:col-span-4">
        <Label for="email">Email</Label>
        <Input id="email" v-model="form.email" disabled type="email" class="mt-1 block w-full" required autocomplete="email" />
        <InputError :message="form.errors.email" class="mt-2" />
      </div>

      <!-- Phone -->
      <div class="col-span-6 sm:col-span-4">
        <Label for="phone">Phone</Label>
        <Input id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" />
        <InputError :message="form.errors.phone" class="mt-2" />
      </div>

      <!-- Role -->
    

      <!-- Is Active -->
     

      <!-- Person ID -->
      <div class="col-span-6 sm:col-span-4">
        <Label for="person_id">Person ID</Label>
        <Input id="person_id" v-model="form.person_id" disabled type="number" class="mt-1 block w-full" />
        <InputError :message="form.errors.person_id" class="mt-2" />
      </div>

      
    </template>

    <template #actions>
      <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </Button>
    </template>
  </FormSection>
</template>
