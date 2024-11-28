<script setup>
import ActionMessage from '@/Components/ActionMessage.vue'
import ActionSection from '@/Components/ActionSection.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import DialogModal from '@/Components/DialogModal.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/shadcn/ui/button/Button.vue'
import Checkbox from '@/Components/shadcn/ui/checkbox/Checkbox.vue'
import Input from '@/Components/shadcn/ui/input/Input.vue'
import Label from '@/Components/shadcn/ui/label/Label.vue'
import Separator from '@/Components/shadcn/ui/separator/Separator.vue'
import { useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'

const props = defineProps({
  tokens: Array,
  availablePermissions: Array,
  defaultPermissions: Array,
})
const route = inject('route')
const createApiTokenForm = useForm({
  name: '',
  permissions: props.defaultPermissions,
})

const updateApiTokenForm = useForm({
  permissions: [],
})

const deleteApiTokenForm = useForm({})

const displayingToken = ref(false)
const managingPermissionsFor = ref(null)
const apiTokenBeingDeleted = ref(null)

function createApiToken() {
  createApiTokenForm.post(route('api-tokens.store'), {
    preserveScroll: true,
    onSuccess: () => {
      displayingToken.value = true
      createApiTokenForm.reset()
    },
  })
}

function manageApiTokenPermissions(token) {
  updateApiTokenForm.permissions = token.abilities
  managingPermissionsFor.value = token
}

function updateApiToken() {
  updateApiTokenForm.put(route('api-tokens.update', managingPermissionsFor.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => (managingPermissionsFor.value = null),
  })
}

function confirmApiTokenDeletion(token) {
  apiTokenBeingDeleted.value = token
}

function deleteApiToken() {
  deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => (apiTokenBeingDeleted.value = null),
  })
}
</script>

<template>
  <div>
    <!-- Generate API Token -->
    <FormSection @submitted="createApiToken">
      <template #title>
        Create API Token
      </template>

      <template #description>
        API tokens allow third-party services to authenticate with our application on your behalf.
      </template>

      <template #form>
        <!-- Token Name -->
        <div class="col-span-6 sm:col-span-4">
          <Label for="name">Name</Label>
          <Input
            id="name" v-model="createApiTokenForm.name" type="text" class="mt-1 block w-full"
            autofocus
          />
          <InputError :message="createApiTokenForm.errors.name" class="mt-2" />
        </div>

        <!-- Token Permissions -->
        <div v-if="availablePermissions.length > 0" class="col-span-6">
          <Label for="permissions">Permissions</Label>

          <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div v-for="permission in availablePermissions" :key="permission" class="flex items-center space-x-2">
              <Checkbox
                :id="`create-${permission}`"
                v-model:checked="createApiTokenForm.permissions"
                :value="permission"
              />
              <label
                :for="`create-${permission}`"
                class="text-sm font-medium leading-none text-gray-600 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-gray-400"
              >
                {{ permission }}
              </label>
            </div>
          </div>
        </div>
      </template>

      <template #actions>
        <ActionMessage :on="createApiTokenForm.recentlySuccessful" class="me-3">
          Created.
        </ActionMessage>

        <Button
          :class="{ 'opacity-25': createApiTokenForm.processing }"
          :disabled="createApiTokenForm.processing"
        >
          Create
        </Button>
      </template>
    </FormSection>

    <div v-if="tokens.length > 0">
      <Separator class="my-8 hidden sm:block" />

      <!-- Manage API Tokens -->
      <div class="mt-10 sm:mt-0">
        <ActionSection>
          <template #title>
            Manage API Tokens
          </template>

          <template #description>
            You may delete any of your existing tokens if they are no longer needed.
          </template>

          <!-- API Token List -->
          <template #content>
            <div class="space-y-6">
              <div v-for="token in tokens" :key="token.id" class="flex items-center justify-between">
                <div class="break-all dark:text-white">
                  {{ token.name }}
                </div>

                <div class="ms-2 flex items-center">
                  <div v-if="token.last_used_ago" class="text-sm text-gray-400">
                    Last used {{ token.last_used_ago }}
                  </div>

                  <button
                    v-if="availablePermissions.length > 0"
                    class="ms-6 cursor-pointer text-sm text-gray-400 underline"
                    @click="manageApiTokenPermissions(token)"
                  >
                    Permissions
                  </button>

                  <button
                    class="ms-6 cursor-pointer text-sm text-red-500"
                    @click="confirmApiTokenDeletion(token)"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </template>
        </ActionSection>
      </div>
    </div>

    <!-- Token Value Modal -->
    <DialogModal :show="displayingToken" @close="displayingToken = false">
      <template #title>
        API Token
      </template>

      <template #content>
        <div>
          Please copy your new API token. For your security, it won't be shown again.
        </div>

        <div
          v-if="$page.props.jetstream.flash.token"
          class="mt-4 break-all rounded bg-gray-100 px-4 py-2 font-mono text-sm text-gray-500 dark:bg-gray-900"
        >
          {{ $page.props.jetstream.flash.token }}
        </div>
      </template>

      <template #footer>
        <Button variant="secondary" @click="displayingToken = false">
          Close
        </Button>
      </template>
    </DialogModal>

    <!-- API Token Permissions Modal -->
    <DialogModal :show="managingPermissionsFor != null" @close="managingPermissionsFor = null">
      <template #title>
        API Token Permissions
      </template>

      <template #content>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div v-for="permission in availablePermissions" :key="permission" class="flex items-center space-x-2">
            <Checkbox
              :id="`update-${permission}`"
              v-model:checked="updateApiTokenForm.permissions"
              :value="permission"
            />
            <label
              :for="`update-${permission}`"
              class="text-sm font-medium leading-none text-gray-600 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-gray-400"
            >
              {{ permission }}
            </label>
          </div>
        </div>
      </template>

      <template #footer>
        <Button variant="secondary" @click="managingPermissionsFor = null">
          Cancel
        </Button>

        <Button
          class="ms-3" :class="{ 'opacity-25': updateApiTokenForm.processing }"
          :disabled="updateApiTokenForm.processing" @click="updateApiToken"
        >
          Save
        </Button>
      </template>
    </DialogModal>

    <!-- Delete Token Confirmation Modal -->
    <ConfirmationModal :show="apiTokenBeingDeleted != null" @close="apiTokenBeingDeleted = null">
      <template #title>
        Delete API Token
      </template>

      <template #content>
        Are you sure you would like to delete this API token?
      </template>

      <template #footer>
        <Button variant="secondary" @click="apiTokenBeingDeleted = null">
          Cancel
        </Button>

        <Button
          variant="destructive" class="ms-3" :class="{ 'opacity-25': deleteApiTokenForm.processing }"
          :disabled="deleteApiTokenForm.processing" @click="deleteApiToken"
        >
          Delete
        </Button>
      </template>
    </ConfirmationModal>
  </div>
</template>
