<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { useLocalStorage } from '@vueuse/core'
import { computed, inject, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import InputError from '@/components/InputError.vue'
import AuthenticationCardLogo from '@/components/LogoRedirect.vue'
import SocialLoginButton from '@/components/SocialLoginButton.vue'
import Button from '@/components/ui/button/Button.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Sonner from '@/components/ui/sonner/Sonner.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { useSeoMetaTags } from '@/composables/useSeoMetaTags.js'

const props = defineProps({
  canResetPassword: Boolean,
  status: String,
  availableOauthProviders: Object,
})

const page = usePage()
const route = inject('route')
const activeTab = useLocalStorage('login-active-tab', 'password')

// Form state
const passwordForm = useForm({
  email: 'test@example.com',
  password: 'password',
  remember: false,
})

const loginLinkForm = useForm({
  email: '',
})

// Computed
const hasOauthProviders = computed(() =>
  Object.keys(props.availableOauthProviders || {}).length > 0,
)

const isProcessing = computed(() =>
  passwordForm.processing || loginLinkForm.processing,
)

// Methods
function handlePasswordLogin() {
  passwordForm
    .transform(data => ({
      ...data,
      remember: data.remember ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => passwordForm.reset('password'),
    })
}

function handleLoginLink() {
  loginLinkForm.post(route('login-link.store'), {
    onSuccess: () => {
      loginLinkForm.reset()
      if (page.props.flash.success) {
        toast.success(page.props.flash.success)
      }
    },
    onError: () => {
      if (page.props.flash.error) {
        toast.error(page.props.flash.error)
      }
    },
  })
}

// Lifecycle
onMounted(() => {
  if (page.props.flash.error) {
    toast.error(page.props.flash.error)
  }

  if (page.props.flash.success) {
    toast.success(page.props.flash.success)
  }
})

// SEO
useSeoMetaTags({
  title: 'Log in',
})
</script>

<template>
  <Sonner position="top-center" />

  <div class="flex min-h-screen flex-col items-center justify-center bg-linear-to-b from-background/50 to-background">
    <Card class="mx-auto w-[420px] shadow-lg transition-all duration-300 hover:shadow-xl">
      <!-- Header -->
      <CardHeader>
        <CardTitle class="flex justify-center">
          <AuthenticationCardLogo />
        </CardTitle>
        <CardDescription class="text-center text-2xl font-light">
          Welcome Back
        </CardDescription>
      </CardHeader>

      <CardContent>
        <!-- Status Message -->
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
          {{ status }}
        </div>

        <!-- Login Tabs -->
        <Tabs v-model="activeTab" class="w-full">
          <TabsList class="grid w-full grid-cols-2 rounded-lg p-1">
            <TabsTrigger value="password">
              Password
            </TabsTrigger>
            <TabsTrigger value="login-link">
              Login Link
            </TabsTrigger>
          </TabsList>

          <div class="mt-6">
            <!-- Password Login -->
            <TabsContent value="password" class="space-y-4">
              <form @submit.prevent="handlePasswordLogin">
                <div class="grid gap-4">
                  <!-- Email -->
                  <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                      id="email"
                      v-model="passwordForm.email"
                      type="email"
                      placeholder="name@example.com"
                      required
                      autofocus
                      autocomplete="username"
                    />
                    <InputError :message="passwordForm.errors.email" />
                  </div>

                  <!-- Password -->
                  <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                      <Label for="password">Password</Label>
                      <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-muted-foreground hover:text-primary hover:underline underline-offset-4"
                      >
                        Forgot password?
                      </Link>
                    </div>
                    <Input
                      id="password"
                      v-model="passwordForm.password"
                      type="password"
                      required
                      autocomplete="current-password"
                    />
                    <InputError :message="passwordForm.errors.password" />
                  </div>

                  <!-- Remember Me -->
                  <div class="flex items-center space-x-2">
                    <Checkbox
                      id="remember"
                      v-model:checked="passwordForm.remember"
                      name="remember"
                    />
                    <label for="remember" class="text-sm text-muted-foreground">
                      Remember me
                    </label>
                  </div>

                  <Button
                    type="submit"
                    class="w-full"
                    :class="{ 'opacity-75': passwordForm.processing }"
                    :disabled="isProcessing"
                  >
                    {{ passwordForm.processing ? 'Signing in...' : 'Sign in' }}
                  </Button>
                </div>
              </form>
            </TabsContent>

            <!-- Login Link -->
            <TabsContent value="login-link" class="space-y-4">
              <div class="text-sm text-muted-foreground">
                We'll send you a login link for password-free sign in.
              </div>
              <form @submit.prevent="handleLoginLink">
                <div class="grid gap-4">
                  <div class="grid gap-2">
                    <Label for="login-link-email">Email</Label>
                    <Input
                      id="login-link-email"
                      v-model="loginLinkForm.email"
                      type="email"
                      required
                      placeholder="name@example.com"
                    />
                    <InputError :message="loginLinkForm.errors.email" />
                  </div>

                  <Button
                    type="submit"
                    class="w-full"
                    :class="{ 'opacity-75': loginLinkForm.processing }"
                    :disabled="isProcessing"
                  >
                    {{ loginLinkForm.processing ? 'Sending...' : 'Send Login Link' }}
                  </Button>
                </div>
              </form>
            </TabsContent>
          </div>
        </Tabs>

        <!-- OAuth Section -->
        <div v-if="hasOauthProviders" class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <span class="w-full border-t" />
            </div>
            <div class="relative flex justify-center text-xs uppercase">
              <span class="bg-background px-2 text-muted-foreground">
                Or continue with
              </span>
            </div>
          </div>

          <div class="mt-6 grid gap-2">
            <SocialLoginButton
              v-for="provider in availableOauthProviders"
              :key="provider.slug"
              :provider="provider"
              :disabled="isProcessing"
            />
          </div>
        </div>

        <!-- Sign Up Link -->
        <div class="mt-6 text-center text-sm text-muted-foreground">
          Don't have an account?
          <Link
            :href="route('register')"
            class="font-medium text-primary hover:underline underline-offset-4"
          >
            Sign up
          </Link>
        </div>
      </CardContent>
    </Card>
  </div>
</template>
