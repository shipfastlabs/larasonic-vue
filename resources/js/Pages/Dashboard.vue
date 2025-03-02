<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/shadcn/ui/avatar';
import { Button } from '@/Components/shadcn/ui/button';
import { CalendarIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/shadcn/ui/card';
import { Checkbox } from '@/Components/shadcn/ui/checkbox';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/shadcn/ui/tabs';
import { Badge } from '@/Components/shadcn/ui/badge';
import { Link } from '@inertiajs/vue3';

// Define props
const props = defineProps({
    student: {
        type: Object,
        required: true,
    },
    stats: {
        type: Array,
        required: true,
    },
    todaysClasses: {
        type: Array,
        required: true,
    },
    currentClass: {
        type: Object,
        default: null, // Can be null
    },
    recentGrades: {
        type: Array,
        required: true
    },
    assignments: { // Keep as placeholders
        type: String,
        required: true,
    },
    exams: { // Keep as placeholders
        type: String,
        required: true,
    },
    announcements: { // Keep as placeholders
        type: String,
        required: true,
    },
    resources: {
        type: String, // Keep as placeholders
        required: true
    }
});

// Sample data - expanded with more relevant student information - REMOVE
// const student = {
//   name: 'Alex Johnson',
//   grade: '10th Grade',
//   avatarUrl: '/api/placeholder/80/80',
//   streak: 5, // Days in a row logging in
// };

const currentDate = new Date().toLocaleDateString('en-US', {
  weekday: 'long',
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});

// Assignments with priority and completion status - REMOVE
// const assignments = ref([
//   { id: 1, title: 'Math Homework - Calculus', dueDate: '2023-10-15', subject: 'Math', priority: 'High', completed: false },
//   { id: 2, title: 'Science Lab Report', dueDate: '2023-10-20', subject: 'Science', priority: 'Medium', completed: false },
//   { id: 3, title: 'English Essay - Shakespeare', dueDate: '2023-10-22', subject: 'English', priority: 'Medium', completed: false },
//   { id: 4, title: 'History Research Paper', dueDate: '2023-10-25', subject: 'History', priority: 'Low', completed: false },
// ]);

// Upcoming exams - REMOVE
// const exams = [
//   { id: 1, subject: 'Math', topic: 'Algebra II', date: '2023-10-18', time: '9:00 AM' },
//   { id: 2, subject: 'Science', topic: 'Chemistry', date: '2023-10-21', time: '10:30 AM' },
// ];

// Expanded grades with more details - REMOVE
// const grades = [
//   { id: 1, subject: 'Math', assignment: 'Quiz 1', grade: 'A', score: '95/100', date: '2023-10-05' },
//   { id: 2, subject: 'Science', assignment: 'Lab Report', grade: 'B+', score: '88/100', date: '2023-10-08' },
//   { id: 3, subject: 'English', assignment: 'Essay', grade: 'A-', score: '91/100', date: '2023-10-10' },
// ];

// Class schedule for today - REMOVE
// const todayClasses = [
//   { id: 1, subject: 'Math', room: '301', time: '8:00 AM - 9:30 AM', teacher: 'Ms. Johnson' },
//   { id: 2, subject: 'English', room: '205', time: '9:45 AM - 11:15 AM', teacher: 'Mr. Williams' },
//   { id: 3, subject: 'Lunch', room: 'Cafeteria', time: '11:15 AM - 12:00 PM', teacher: null },
//   { id: 4, subject: 'Science', room: 'Lab 2', time: '12:15 PM - 1:45 PM', teacher: 'Dr. Martinez' },
//   { id: 5, subject: 'History', room: '108', time: '2:00 PM - 3:30 PM', teacher: 'Mrs. Thompson' },
// ];

// Announcements - REMOVE
// const announcements = [
//   { id: 1, title: 'Spirit Week Next Week', date: '2023-10-12', content: 'Don\'t forget to participate in theme days!' },
//   { id: 2, title: 'Winter Break Dates', date: '2023-10-10', content: 'Winter break will be from Dec 20 to Jan 5' },
// ];

// Resources by subject - REMOVE
// const resources = {
//   Math: [
//     { id: 1, title: 'Algebra Formula Sheet', type: 'PDF' },
//     { id: 2, title: 'Calculus Tutorial Videos', type: 'Video' },
//   ],
//   Science: [
//     { id: 3, title: 'Periodic Table Reference', type: 'PDF' },
//     { id: 4, title: 'Lab Safety Guidelines', type: 'PDF' },
//   ],
//   English: [
//     { id: 5, title: 'Essay Writing Guide', type: 'PDF' },
//     { id: 6, title: 'Shakespeare Analysis Resources', type: 'Link' },
//   ]
// };

// Stats for quick overview - REMOVE
// const stats = [
//   { label: 'GPA', value: '3.8' },
//   { label: 'Attendance', value: '98%' },
//   { label: 'Assignments Due', value: assignments.value.filter(a => !a.completed).length },
//   { label: 'Upcoming Exams', value: exams.length },
// ];

// Helper function to get priority color
const getPriorityColor = (priority) => {
  switch (priority) {
    case 'High': return 'bg-red-100 text-red-600';
    case 'Medium': return 'bg-yellow-100 text-yellow-600';
    case 'Low': return 'bg-green-100 text-green-600';
    default: return 'bg-gray-100 text-gray-600';
  }
};

// Toggle assignment completion - KEEP, but modify to work with the "Coming Soon" state
const toggleCompletion = (id) => {
    // Since assignments are "coming soon", this function won't do anything for now.
    // You can add a console.log or a temporary message if you want.
    console.log("Toggle completion clicked for assignment ID:", id); // Debugging
};

// Filter assignments based on completion status - KEEP, but modify to work with "Coming Soon"
const completedAssignments = computed(() => []); // Return empty array
const pendingAssignments = computed(() => []);   // Return empty array

// Get current/next class based on time - REMOVE
// const currentClass = computed(() => {
//   // This would normally use actual time comparison
//   return todayClasses[1]; // Mock for demonstration
// });
</script>

<template>
  <AppLayout>
    <div class="md:container mx-auto px-4 py-6 space-y-6">
      <!-- Top Navigation Bar with Quick Actions -->
      <header class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <Avatar>
            <AvatarImage :src="student.avatarUrl" alt="Student" />
            <AvatarFallback>AJ</AvatarFallback>
          </Avatar>
          <div>
            <h1 class="font-bold text-xl">{{ student.name }}</h1>
            <p class="text-sm text-muted-foreground">{{ student.grade }} | {{ currentDate }}</p>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <Button variant="outline" size="icon">
            <span class="sr-only">Notifications</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
          </Button>
          <Button variant="outline" size="icon">
            <span class="sr-only">Messages</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
          </Button>
          <Button variant="outline" size="icon">
            <span class="sr-only">Settings</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </Button>
        </div>
      </header>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <Card v-for="(stat, index) in stats" :key="index">
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">{{ stat.label }}</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ stat.value }}</div>
          </CardContent>
        </Card>
      </div>

      <!-- Today's Schedule and Current Class -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current/Next Class Card -->
        <Card v-if="currentClass" class="lg:col-span-1">
          <CardHeader>
            <CardTitle>Current Class</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="font-bold text-xl">{{ currentClass.subject }}</h3>
                <p class="text-muted-foreground">{{ currentClass.teacher }}</p>
              </div>
              <Badge variant="secondary">Room {{ currentClass.room }}</Badge>
            </div>
            <div class="flex items-center text-muted-foreground mb-4">
              <CalendarIcon class="w-5 h-5 mr-2" />
              {{ currentClass.time }}
            </div>
            <Button class="w-full">View Class Materials</Button>
          </CardContent>
        </Card>
        <Card v-else class="lg:col-span-1">
            <CardHeader>
                <CardTitle>No Current Class</CardTitle>
            </CardHeader>
            <CardContent>
                <p class="text-muted-foreground">
                    There is no class in session at this time.
                </p>
            </CardContent>
        </Card>

        <!-- Today's Schedule -->
        <Card class="lg:col-span-2">
          <CardHeader class="flex flex-row items-center justify-between">
            <CardTitle>Today's Schedule</CardTitle>
            <Link :href="route('schedule.index')">
                <Button variant="link" size="sm" class="flex items-center">
                    View Full Schedule
                    <ChevronRightIcon class="w-4 h-4 ml-1" />
                </Button>
            </Link>
          </CardHeader>
          <CardContent>
            <div class="space-y-3 max-h-72 overflow-y-auto">
              <div v-for="(class_item, index) in todaysClasses" :key="class_item.id"
                class="flex items-center p-3 border-l-4 rounded"
                :class="[index === 1 ? 'border-indigo-500' : 'border-gray-200']"> <!-- Consider dynamic highlighting -->
                <div class="w-16 flex-shrink-0 flex flex-col items-center justify-center">
                  <span class="text-sm font-bold">{{ class_item.time.split(' - ')[0] }}</span>
                </div>
                <div class="flex-grow ml-3">
                  <div class="flex justify-between items-center">
                    <h4 class="font-bold">{{ class_item.subject }}</h4>
                    <span class="text-xs font-medium bg-gray-200 rounded-full px-2 py-1">Room {{ class_item.room }}</span>
                  </div>
                  <p v-if="class_item.teacher" class="text-sm text-muted-foreground">{{ class_item.teacher }}</p>
                </div>
              </div>
                <div v-if="todaysClasses.length === 0" class="text-center py-4 text-muted-foreground">
                    No classes scheduled for today.
                </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Main Dashboard Content - 2 Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Assignments and Exams -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Assignments Card -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between">
              <CardTitle>Assignments</CardTitle>
              <Button>Add New</Button>
            </CardHeader>
            <CardContent>
              <Tabs default-value="pending">
                <TabsList class="mb-4">
                  <TabsTrigger value="pending">Pending</TabsTrigger>
                  <TabsTrigger value="completed">Completed</TabsTrigger>
                </TabsList>
                <TabsContent value="pending">
                  <div class="space-y-3 max-h-80 overflow-y-auto">
                    <!--  REMOVED v-for -->
                    <div v-if="pendingAssignments.length === 0" class="text-center py-4 text-muted-foreground">
                      Coming Soon!
                    </div>
                  </div>
                </TabsContent>
                <TabsContent value="completed">
                  <div class="space-y-3 max-h-80 overflow-y-auto">
                    <!-- REMOVED v-for -->
                      <div  class="text-center py-4 text-muted-foreground">
                        Coming Soon
                      </div>
                  </div>
                </TabsContent>
              </Tabs>
            </CardContent>
          </Card>

          <!-- Exams Card -->
          <Card>
            <CardHeader>
              <CardTitle>Upcoming Exams</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-3">
                <!-- REMOVED v-for -->
                  <div class="text-center py-4 text-muted-foreground">
                    Coming Soon!
                  </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Right Column - Grades, Announcements, Resources -->
        <div class="space-y-6">
          <!-- Recent Grades Card -->
          <Card>
            <CardHeader class="flex flex-row items-center justify-between">
              <CardTitle>Recent Grades</CardTitle>
              <Button variant="ghost" size="sm" class="flex items-center">
                View All
                <ChevronRightIcon class="w-4 h-4 ml-1" />
              </Button>
            </CardHeader>
            <CardContent>
              <div class="space-y-3">
                <div v-for="grade in recentGrades" :key="grade.id" class="p-3 border rounded-lg">
                  <div class="flex justify-between items-center">
                    <span class="text-lg font-bold" :class="{
                      'text-green-600': grade.grade.startsWith('A'),
                      'text-blue-600': grade.grade.startsWith('B'),
                      'text-yellow-600': grade.grade.startsWith('C'),
                      'text-orange-600': grade.grade.startsWith('D'),
                      'text-red-600': grade.grade.startsWith('F')
                    }">{{ grade.grade }}</span>
                    <span class="text-sm text-muted-foreground">{{ grade.date }}</span>
                  </div>
                  <p class="font-medium">{{ grade.subject }} - {{ grade.assignment }}</p>
                  <p class="text-sm text-muted-foreground">{{ grade.score }}</p>
                </div>
                  <div v-if="recentGrades.length === 0" class="text-center py-4 text-muted-foreground">
                      No recent grades available.
                  </div>
              </div>
            </CardContent>
          </Card>

          <!-- Announcements Card -->
          <Card>
            <CardHeader>
              <CardTitle>Announcements</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-3">
                <!-- REMOVED v-for -->
                  <div class="text-center py-4 text-muted-foreground">
                    Coming Soon!
                  </div>
              </div>
            </CardContent>
          </Card>

          <!-- Resources Card -->
          <Card>
            <CardHeader>
              <CardTitle>Learning Resources</CardTitle>
            </CardHeader>
            <CardContent>
              <!-- Subject Tabs -->
              <Tabs default-value="math">
                <TabsList class="mb-4">
                  <TabsTrigger value="math">Math</TabsTrigger>
                  <TabsTrigger value="science">Science</TabsTrigger>
                  <TabsTrigger value="english">English</TabsTrigger>
                  <TabsTrigger value="history">History</TabsTrigger>
                </TabsList>
                <TabsContent value="math">
                    <div class="space-y-2">
                        <!-- REMOVED v-for -->
                            <div class="text-center py-4 text-muted-foreground">
                                Coming Soon!
                            </div>
                    </div>
                </TabsContent>
                <TabsContent value="science">
                    <!--  Science resources here -->
                    <div class="text-center py-4 text-muted-foreground">
                        Coming Soon!
                    </div>
                </TabsContent>
                <TabsContent value="english">
                    <!-- English resources here -->
                    <div class="text-center py-4 text-muted-foreground">
                        Coming Soon!
                    </div>
                </TabsContent>
                <TabsContent value="history">
                    <!--  History resources here -->
                    <div class="text-center py-4 text-muted-foreground">
                        Coming Soon!
                    </div>
                </TabsContent>
              </Tabs>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
