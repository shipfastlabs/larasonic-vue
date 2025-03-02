<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    changelogHtml: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <AppLayout title="Changelog">
        <div class="container mx-auto px-4 py-6 prose dark:prose-invert max-w-3xl">
            <!-- This is where the parsed Markdown will be rendered -->
            <div v-html="changelogHtml"></div>
        </div>
    </AppLayout>
</template> 