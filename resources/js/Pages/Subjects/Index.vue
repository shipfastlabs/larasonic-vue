<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/Components/shadcn/ui/card';
import { Badge } from '@/Components/shadcn/ui/badge';
import { Separator } from '@/Components/shadcn/ui/separator';
import { computed, ref } from 'vue';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/shadcn/ui/accordion";
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/Components/shadcn/ui/table"
import { Input } from "@/Components/shadcn/ui/input";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/shadcn/ui/select";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/shadcn/ui/popover";
import { Button } from "@/Components/shadcn/ui/button";
import { CalendarIcon } from "lucide-vue-next";
import { Calendar } from "@/Components/shadcn/ui/calendar";
import { format } from "date-fns";
import { cn } from "@/lib/utils";
import { Progress } from "@/Components/shadcn/ui/progress";

const props = defineProps({
    completedSubjects: {
        type: Array,
        required: true,
    },
    ongoingSubjects: {
        type: Array,
        required: true,
    },
    incompleteSubjects: {
        type: Array,
        required: true,
    },
    course: { // Course information
        type: Object,
        required: true,
    },
});

// --- State ---
const searchQuery = ref('');
const selectedStatus = ref('All');
const selectedYear = ref('All');
const selectedSemester = ref('All');
const date = ref()

// --- Computed Properties ---
const allSubjects = computed(() => {
    return [
        ...props.completedSubjects.map(s => ({ ...s, status: 'Completed' })),
        ...props.ongoingSubjects.map(s => ({ ...s, status: 'Ongoing' })),
        ...props.incompleteSubjects.map(s => ({ ...s, status: 'Incomplete' })),
    ];
});

// Extract unique years and semesters for filters
const uniqueYears = computed(() => {
    const years = new Set(allSubjects.value.map(s => s.academic_year));
    return ['All', ...Array.from(years).sort()]; // Add 'All' and sort
});

const uniqueSemesters = computed(() => {
    const semesters = new Set(allSubjects.value.map(s => s.semester));
    return ['All', ...Array.from(semesters).sort()];  // Add 'All' and sort
});

const filteredSubjects = computed(() => {
    let filtered = allSubjects.value;

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(subject =>
            subject.code.toLowerCase().includes(query) ||
            subject.title.toLowerCase().includes(query)
        );
    }

    if (selectedStatus.value !== 'All') {
        filtered = filtered.filter(subject => subject.status === selectedStatus.value);
    }

    if (selectedYear.value !== 'All') {
        filtered = filtered.filter(subject => subject.academic_year === parseInt(selectedYear.value));
    }

    if (selectedSemester.value !== 'All') {
        filtered = filtered.filter(subject => subject.semester === parseInt(selectedSemester.value));
    }

    return filtered;
});

// --- Computed Properties for Visualization ---

const completedCount = computed(() => props.completedSubjects.length);
const ongoingCount = computed(() => props.ongoingSubjects.length);
const incompleteCount = computed(() => props.incompleteSubjects.length);
const totalSubjectsCount = computed(() => allSubjects.value.length);

const completionPercentage = computed(() => {
    if (totalSubjectsCount.value === 0) return 0;
    return (completedCount.value / totalSubjectsCount.value) * 100;
});

const getStatusBadgeVariant = (status) => {
    switch (status) {
        case 'Completed': return 'success';
        case 'Ongoing': return 'warning';
        case 'Incomplete': return 'destructive';
        default: return 'default';
    }
};

</script>

<template>
    <AppLayout title="Subjects">
        <div class="container mx-auto px-4 py-6 space-y-6">
            <h1 class="text-2xl font-bold">Subject Status</h1>
            <p class="text-muted-foreground">
                Course: {{ course.code }} - {{ course.title }}
            </p>

            <!-- Filters -->
            <div class="flex flex-col md:flex-row gap-4">
                <Input type="text" v-model="searchQuery" placeholder="Search subjects..." class="max-w-sm" />

                <Select v-model="selectedStatus">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Select Status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="All">All</SelectItem>
                        <SelectItem value="Completed">Completed</SelectItem>
                        <SelectItem value="Ongoing">Ongoing</SelectItem>
                        <SelectItem value="Incomplete">Incomplete</SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="selectedYear">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Select Year" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="year in uniqueYears" :key="year" :value="year">
                            {{ year === 'All' ? 'All' : year }}
                        </SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="selectedSemester">
                    <SelectTrigger class="w-[180px]">
                        <SelectValue placeholder="Select Semester" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="semester in uniqueSemesters" :key="semester" :value="semester">
                            {{ semester === 'All' ? 'All' : semester }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Visualization Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Completed</CardTitle>
                        <CardDescription>Subjects you have passed.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ completedCount }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Ongoing</CardTitle>
                        <CardDescription>Subjects currently in progress.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ ongoingCount }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Incomplete</CardTitle>
                        <CardDescription>Subjects you have not yet taken.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ incompleteCount }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Overall Progress -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Overall Progress</CardTitle>
                    <CardDescription>Your progress towards completing all subjects.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Progress :value="completionPercentage" class="mb-2" />
                    <div class="text-sm text-muted-foreground">{{ completionPercentage.toFixed(2) }}% Complete</div>
                </CardContent>
            </Card>

            <!-- Subject Table -->
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Code</TableHead>
                        <TableHead>Title</TableHead>
                        <TableHead>Units</TableHead>
                        <TableHead>Grade</TableHead>
                        <TableHead>Year</TableHead>
                        <TableHead>Semester</TableHead>
                        <TableHead>Status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="subject in filteredSubjects" :key="subject.id">
                        <TableCell>{{ subject.code }}</TableCell>
                        <TableCell>{{ subject.title }}</TableCell>
                        <TableCell>{{ subject.units }}</TableCell>
                        <TableCell>{{ subject.grade ?? '-' }}</TableCell>
                        <TableCell>{{ subject.academic_year }}</TableCell>
                        <TableCell>{{ subject.semester }}</TableCell>
                        <TableCell>
                            <Badge :variant="getStatusBadgeVariant(subject.status)">{{ subject.status }}</Badge>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="filteredSubjects.length === 0">
                        <TableCell colspan="7" class="text-center">No subjects found matching your criteria.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template> 