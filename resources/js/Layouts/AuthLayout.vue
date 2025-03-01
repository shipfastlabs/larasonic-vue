<script setup lang="ts">
import AuthLayout from '@/Layouts/auth/AuthSimpleLayout.vue';
import Sonner from '@/Components/shadcn/ui/sonner/Sonner.vue'
defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Sonner position="top-center" />
    <transition name="fade" mode="out-in">
        <AuthLayout :title="title" :description="description">
            <slot />
        </AuthLayout>
    </transition>
</template>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
