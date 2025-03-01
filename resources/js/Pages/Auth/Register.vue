<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import TextLink from '@/Components/TextLink.vue';
import { Button } from '@/Components/shadcn/ui/button';
import { Input } from '@/Components/shadcn/ui/input';
import { Label } from '@/Components/shadcn/ui/label';
import AuthBase from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import axios from 'axios';

const step = ref(1);
const userType = ref('');
const emailForm = useForm({
    email: '',
});
const idForm = useForm({
    id: '',
});
const finalForm = useForm({
    id: '',
    email: '',
    name: '',
    phone: '',
    password: '',
    password_confirmation: '',
    role: '',
    person_id: '',
    person_type: '',
});

const nextStep = async () => {
    try {
        if (step.value === 1) {
            if (!userType.value) {
                toast.error('Please select a user type.');
                return;
            }
            finalForm.role = userType.value;
            step.value++;
        } else if (step.value === 2) {
            if (!emailForm.email) {
                toast.error('Please enter your email.');
                return;
            }

            const response = await axios.post('/api/check-email', { email: emailForm.email, userType: userType.value });
            if (response.data.success) {
                toast.success(response.data.success);
                finalForm.email = emailForm.email;
                step.value++;
            } else {
                toast.error(response.data.error);
            }
        } else if (step.value === 3) {
            const response = await axios.post('/api/check-id', { id: idForm.id, userType: userType.value });
            if (response.data.success) {
                toast.success(response.data.success);
                finalForm.id = idForm.id;
                finalForm.name = response.data.fullName;
                step.value++;
            } else {
                toast.error(response.data.error);
            }
        }
    } catch (error) {
        console.error('Error during API call:', error);
        toast.error('An error occurred while processing your request. Please try again.');
    }
};

const submit = () => {
    finalForm.post(route('register'), {
        onFinish: () => finalForm.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" />

        <form @submit.prevent="step === 4 ? submit() : nextStep()" class="flex flex-col gap-6">
            <div v-if="step === 1" class="flex flex-col">
                <Label>Select your role:</Label>
                <div class="flex gap-4 mt-2">
                    <label class="flex items-center">
                        <input type="radio" value="student" v-model="userType" required class="mr-2" />
                        Student
                    </label>
                    <label class="flex items-center">
                        <input type="radio" value="instructor" v-model="userType" required class="mr-2" />
                        Instructor
                    </label>
                </div>
            </div>

            <div v-if="step === 2" class="flex flex-col">
                <Label for="email">Email Address</Label>
                <Input id="email" type="email" required v-model="emailForm.email" placeholder="email@example.com" class="mt-2" />
                <InputError :message="emailForm.errors.email" />
            </div>

            <div v-if="step === 3" class="flex flex-col">
                <Label for="id">Your ID</Label>
                <Input id="id" type="text" required v-model="idForm.id" placeholder="Enter your ID" class="mt-2" />
                <InputError :message="idForm.errors.id" />
            </div>

            <div v-if="step === 4" class="flex flex-col">
                <Label for="name">Full Name</Label>
                <Input id="name" type="text" required v-model="finalForm.name" placeholder="Your full name" class="mt-2" />
                <InputError :message="finalForm.errors.name" />
            </div>

            <div v-if="step === 4" class="flex flex-col">
                <Label for="phone">Phone Number</Label>
                <Input id="phone" type="text" v-model="finalForm.phone" placeholder="Your phone number" class="mt-2" />
                <InputError :message="finalForm.errors.phone" />
            </div>

            <div v-if="step === 4" class="flex flex-col">
                <Label for="password">Password</Label>
                <Input id="password" type="password" required v-model="finalForm.password" placeholder="Password" class="mt-2" />
                <InputError :message="finalForm.errors.password" />
            </div>

            <div v-if="step === 4" class="flex flex-col">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input id="password_confirmation" type="password" required v-model="finalForm.password_confirmation" placeholder="Confirm Password" class="mt-2" />
                <InputError :message="finalForm.errors.password_confirmation" />
            </div>

            <Button type="submit" class="mt-4 w-full" :disabled="emailForm.processing || idForm.processing || finalForm.processing">
                <LoaderCircle v-if="emailForm.processing || idForm.processing || finalForm.processing" class="h-4 w-4 animate-spin" />
                {{ step === 4 ? 'Create Account' : 'Next' }}
            </Button>

            <div class="text-center text-sm text-muted-foreground mt-4">
                Already have an account?
                <TextLink :href="route('login')" class="underline underline-offset-4">Log in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>

<style scoped>
/* Add any additional styles for the radio buttons or layout here */
</style>
