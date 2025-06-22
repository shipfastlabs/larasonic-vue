<script setup>
import { Icon } from '@iconify/vue'
import { router, usePage } from '@inertiajs/vue3'
import { useFetch } from '@vueuse/core'
import { inject, onMounted, ref } from 'vue'
import { toast } from 'vue-sonner'

import InputError from '@/components/InputError.vue'
import Alert from '@/components/ui/alert/Alert.vue'
import AlertDescription from '@/components/ui/alert/AlertDescription.vue'
import AlertTitle from '@/components/ui/alert/AlertTitle.vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import Skeleton from '@/components/ui/skeleton/Skeleton.vue'
import { Textarea } from '@/components/ui/textarea'
import AppLayout from '@/Layouts/AppLayout.vue'
import ModelSelector from './Components/ModelSelector.vue'
import TemperatureSelector from './Components/TemperatureSelector.vue'

const props = defineProps({
  systemPrompt: String,
  models: Array,
  subscriptionEnabled: Boolean,
})

const route = inject('route')

const userInput = ref('')
const modelOutput = ref(null)
const error = ref(null)

const selectedModel = ref(props.models[0])
function onModelSelect(model) {
  selectedModel.value = model
}

async function submit() {
  if (!userInput.value.trim()) {
    error.value = 'Please enter a message'
    return
  }

  error.value = null

  const { data, error: fetchError } = await useFetch('/prism/openai/v1/chat/completions', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      model: selectedModel.value,
      messages: [
        { role: 'user', content: userInput.value },
      ],
    }),
  }).json()

  if (fetchError.value) {
    error.value = 'An error occurred while submitting your message.'
  }
  else {
    modelOutput.value = data.value.choices[0].message.content
  }
}

onMounted(() => {
  if (!props.subscriptionEnabled) {
    const promise = new Promise((resolve, _reject) => {
      setTimeout(() => {
        resolve(route('subscriptions.create'))
      }, 5000)
    })

    toast.promise(promise, {
      loading: 'Pro Subscription Required to use this feature.',
      success: (data) => {
        router.visit(data)
      },
    })
  }
})

const title = `${usePage().props.name} - AI Playground`
</script>

<template>
  <AppLayout :title="title">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight">
        {{ $page.props.name }} AI Playground
      </h2>
    </template>
    <div class="h-full flex-col flex">
      <Alert class="mb-4">
        <Icon icon="lucide:info" class="size-4" />
        <AlertTitle>Demo AI Chat</AlertTitle>
        <AlertDescription>
          This is a demo AI chat. You can use it to test the AI chat. And This is subscrition protected page
          so you
          can't use it without subscription.
        </AlertDescription>
      </Alert>
      <div v-if="props.subscriptionEnabled" class="flex-1">
        <div class="h-full">
          <div class="grid h-full items-stretch gap-6 md:grid-cols-[minmax(0,1fr)_180px]">
            <div class="flex-col space-y-4 flex md:order-2">
              <ModelSelector :models="props.models" @update:model-select="onModelSelect" />
              <TemperatureSelector />
            </div>
            <div class="order-1">
              <div class="mt-0 border-0 p-0">
                <div class="flex flex-col space-y-4">
                  <div class="grid h-full gap-6 lg:grid-cols-2">
                    <div class="flex flex-col space-y-4">
                      <div class="flex flex-1 flex-col space-y-2">
                        <Label for="input">System Input</Label>
                        <Textarea
                          id="input" disabled :default-value="systemPrompt"
                          class="flex-1 lg:min-h-[320px]"
                        />
                      </div>
                      <div class="flex flex-col space-y-2">
                        <Label for="instructions">User Input</Label>
                        <Textarea
                          id="instructions" v-model="userInput"
                          class="lg:min-h-[320px]"
                        />
                      </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                      <Label for="output">Model Output</Label>
                      <Skeleton
                        v-if="!modelOutput"
                        class="h-20 sm:h-full min-h-[400px]lg:min-h-[700px] rounded-md"
                      />
                      <Textarea
                        v-else id="output" disabled
                        class="min-h-[400px] rounded-md bg-muted border lg:min-h-[700px]"
                        :default-value="modelOutput"
                      />
                    </div>
                  </div>
                  <div v-if="!modelOutput" class="flex items-center space-x-2">
                    <Button @click.prevent="submit">
                      <span>Submit</span>
                    </Button>
                    <InputError :message="error" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
