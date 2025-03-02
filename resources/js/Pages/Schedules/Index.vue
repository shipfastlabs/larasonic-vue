<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/Components/shadcn/ui/card';
import { Button } from '@/Components/shadcn/ui/button';
import { ref } from 'vue';

const props = defineProps({
    schedules: {
        type: Array,
        required: true,
    },
});

const daysOfWeek = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
const viewMode = ref('list'); // Default view mode

const toggleViewMode = (mode) => {
    viewMode.value = mode;
};

console.log(props.schedules);
</script>

<template>
    <AppLayout title="My Schedule">
        <div class="md:container mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold mb-4">My Full Schedule</h1>

            <div class="flex justify-between mb-4">
                <div>
                    <Button variant="outline" @click="toggleViewMode('list')">List View</Button>
                    <Button variant="outline" @click="toggleViewMode('column')">Column View</Button>
                    <Button variant="outline" @click="toggleViewMode('timeline')">Timeline View</Button>
                </div>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Weekly Schedule</CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="viewMode === 'list'" class="space-y-4">
                        <div v-for="day in daysOfWeek" :key="day" class="p-4 border rounded-lg">
                            <h2 class="font-semibold text-lg mb-2">{{ day }}</h2>
                            <div v-for="schedule in schedules.filter(s => s.day_of_week === day)" :key="schedule.id" class="mb-2 p-2 border rounded-md bg-gray-50">
                                <h3 class="font-bold text-md">{{ schedule.subject }}</h3>
                                <p class="text-sm text-gray-600">{{ schedule.time }}</p>
                                <p class="text-sm text-gray-600">Room: {{ schedule.room }}</p>
                                <p class="text-sm text-gray-600">Teacher: {{ schedule.teacher }}</p>
                            </div>
                            <div v-if="!schedules.some(s => s.day_of_week === day)" class="text-gray-500 text-sm">
                                No classes scheduled.
                            </div>
                        </div>
                    </div>

                    <div v-else-if="viewMode === 'column'" class="grid grid-cols-1 md:grid-cols-7 gap-4">
                        <div v-for="day in daysOfWeek" :key="day" class="p-4 border rounded-lg">
                            <h2 class="font-semibold text-lg mb-2">{{ day }}</h2>
                            <div v-for="schedule in schedules.filter(s => s.day_of_week === day)" :key="schedule.id" class="mb-2 p-2 border rounded-md bg-gray-50">
                                <h3 class="font-bold text-md">{{ schedule.subject }}</h3>
                                <p class="text-sm text-gray-600">{{ schedule.time }}</p>
                                <p class="text-sm text-gray-600">Room: {{ schedule.room }}</p>
                                <p class="text-sm text-gray-600">Teacher: {{ schedule.teacher }}</p>
                            </div>
                            <div v-if="!schedules.some(s => s.day_of_week === day)" class="text-gray-500 text-sm">
                                No classes scheduled.
                            </div>
                        </div>
                    </div>

                    <div v-else-if="viewMode === 'timeline'" class="relative">
                        <div class="absolute inset-0 border-l-2 border-gray-300"></div>
                        <div class="flex flex-col space-y-4">
                            <div v-for="schedule in schedules" :key="schedule.id" class="flex items-center">
                                <div class="w-16 flex-shrink-0">
                                    <span class="text-sm font-bold">{{ schedule.time }}</span>
                                </div>
                                <div class="flex-grow p-2 border rounded-md bg-gray-50">
                                    <h3 class="font-bold text-md">{{ schedule.subject }}</h3>
                                    <p class="text-sm text-gray-600">Room: {{ schedule.room }}</p>
                                    <p class="text-sm text-gray-600">Teacher: {{ schedule.teacher }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template> 