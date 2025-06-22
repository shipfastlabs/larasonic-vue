<script setup>
import { useForm } from '@inertiajs/vue3'
import { inject, ref } from 'vue'
import { toast } from 'vue-sonner'
import ActionSection from '@/components/ActionSection.vue'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
import FormSection from '@/components/FormSection.vue'
import InputError from '@/components/InputError.vue'
import Button from '@/components/ui/button/Button.vue'
import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Separator from '@/components/ui/separator/Separator.vue'

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
      toast.success('Token has been created')
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
    onSuccess: () => {
      managingPermissionsFor.value = null
      toast.success('Permissions have been updated')
    },
  })
}

function confirmApiTokenDeletion(token) {
  apiTokenBeingDeleted.value = token
}

function deleteApiToken() {
  deleteApiTokenForm.delete(route('api-tokens.destroy', apiTokenBeingDeleted.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      apiTokenBeingDeleted.value = null
      toast.success('Token has been deleted')
    },
  })
}

function hasPermission(permissions, permission) {
  return permissions.includes(permission)
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
                :checked="hasPermission(createApiTokenForm.permissions, permission)"
                @update:checked="(checked) => {
                  if (checked) {
                    createApiTokenForm.permissions.push(permission)
                  }
                  else {
                    createApiTokenForm.permissions = createApiTokenForm.permissions.filter(p => p !== permission)
                  }
                }"
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
    <Dialog :open="displayingToken" @update:open="displayingToken = false">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>API Token</DialogTitle>
          <DialogDescription>
            Please copy your new API token. For your security, it won't be shown again.
          </DialogDescription>
        </DialogHeader>

        <div
          v-if="$page.props.jetstream.flash.token"
          class="mt-4 break-all rounded bg-gray-100 px-4 py-2 font-mono text-sm text-gray-500 dark:bg-gray-900"
        >
          {{ $page.props.jetstream.flash.token }}
        </div>

        <DialogFooter>
          <Button variant="secondary" @click="displayingToken = false">
            Close
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- API Token Permissions Modal -->
    <Dialog :open="managingPermissionsFor != null" @update:open="(value) => !value && (managingPermissionsFor = null)">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>API Token Permissions</DialogTitle>
          <DialogDescription>
            Update the permissions for this API token.
          </DialogDescription>
        </DialogHeader>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div v-for="permission in availablePermissions" :key="permission" class="flex items-center space-x-2">
            <Checkbox
              :id="`update-${permission}`"
              :checked="hasPermission(updateApiTokenForm.permissions, permission)"
              @update:checked="(checked) => {
                if (checked) {
                  updateApiTokenForm.permissions.push(permission)
                }
                else {
                  updateApiTokenForm.permissions = updateApiTokenForm.permissions.filter(p => p !== permission)
                }
              }"
            />
            <label
              :for="`update-${permission}`"
              class="text-sm font-medium leading-none text-gray-600 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-gray-400"
            >
              {{ permission }}
            </label>
          </div>
        </div>

        <DialogFooter>
          <Button variant="secondary" @click="managingPermissionsFor = null">
            Cancel
          </Button>

          <Button
            class="ms-3" :class="{ 'opacity-25': updateApiTokenForm.processing }"
            :disabled="updateApiTokenForm.processing"
            @click="updateApiToken"
          >
            Save
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

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
