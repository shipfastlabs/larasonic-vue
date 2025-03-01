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


// Sample data - expanded with more relevant student information
const student = {
  name: 'Alex Johnson',
  grade: '10th Grade',
  avatarUrl: '/api/placeholder/80/80',
  streak: 5, // Days in a row logging in
};

const currentDate = new Date().toLocaleDateString('en-US', {
  weekday: 'long',
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});

// Assignments with priority and completion status
const assignments = ref([
  { id: 1, title: 'Math Homework - Calculus', dueDate: '2023-10-15', subject: 'Math', priority: 'High', completed: false },
  { id: 2, title: 'Science Lab Report', dueDate: '2023-10-20', subject: 'Science', priority: 'Medium', completed: false },
  { id: 3, title: 'English Essay - Shakespeare', dueDate: '2023-10-22', subject: 'English', priority: 'Medium', completed: false },
  { id: 4, title: 'History Research Paper', dueDate: '2023-10-25', subject: 'History', priority: 'Low', completed: false },
]);

// Upcoming exams
const exams = [
  { id: 1, subject: 'Math', topic: 'Algebra II', date: '2023-10-18', time: '9:00 AM' },
  { id: 2, subject: 'Science', topic: 'Chemistry', date: '2023-10-21', time: '10:30 AM' },
];

// Expanded grades with more details
const grades = [
  { id: 1, subject: 'Math', assignment: 'Quiz 1', grade: 'A', score: '95/100', date: '2023-10-05' },
  { id: 2, subject: 'Science', assignment: 'Lab Report', grade: 'B+', score: '88/100', date: '2023-10-08' },
  { id: 3, subject: 'English', assignment: 'Essay', grade: 'A-', score: '91/100', date: '2023-10-10' },
];

// Class schedule for today
const todayClasses = [
  { id: 1, subject: 'Math', room: '301', time: '8:00 AM - 9:30 AM', teacher: 'Ms. Johnson' },
  { id: 2, subject: 'English', room: '205', time: '9:45 AM - 11:15 AM', teacher: 'Mr. Williams' },
  { id: 3, subject: 'Lunch', room: 'Cafeteria', time: '11:15 AM - 12:00 PM', teacher: null },
  { id: 4, subject: 'Science', room: 'Lab 2', time: '12:15 PM - 1:45 PM', teacher: 'Dr. Martinez' },
  { id: 5, subject: 'History', room: '108', time: '2:00 PM - 3:30 PM', teacher: 'Mrs. Thompson' },
];

// Announcements
const announcements = [
  { id: 1, title: 'Spirit Week Next Week', date: '2023-10-12', content: 'Don\'t forget to participate in theme days!' },
  { id: 2, title: 'Winter Break Dates', date: '2023-10-10', content: 'Winter break will be from Dec 20 to Jan 5' },
];

// Resources by subject
const resources = {
  Math: [
    { id: 1, title: 'Algebra Formula Sheet', type: 'PDF' },
    { id: 2, title: 'Calculus Tutorial Videos', type: 'Video' },
  ],
  Science: [
    { id: 3, title: 'Periodic Table Reference', type: 'PDF' },
    { id: 4, title: 'Lab Safety Guidelines', type: 'PDF' },
  ],
  English: [
    { id: 5, title: 'Essay Writing Guide', type: 'PDF' },
    { id: 6, title: 'Shakespeare Analysis Resources', type: 'Link' },
  ]
};

// Stats for quick overview
const stats = [
  { label: 'GPA', value: '3.8' },
  { label: 'Attendance', value: '98%' },
  { label: 'Assignments Due', value: assignments.value.filter(a => !a.completed).length },
  { label: 'Upcoming Exams', value: exams.length },
];

// Helper function to get priority color
const getPriorityColor = (priority) => {
  switch (priority) {
    case 'High': return 'bg-red-100 text-red-600';
    case 'Medium': return 'bg-yellow-100 text-yellow-600';
    case 'Low': return 'bg-green-100 text-green-600';
    default: return 'bg-gray-100 text-gray-600';
  }
};

// Toggle assignment completion
const toggleCompletion = (id) => {
  const index = assignments.value.findIndex(a => a.id === id);
  if (index !== -1) {
    assignments.value[index].completed = !assignments.value[index].completed;
  }
};

// Filter assignments based on completion status
const completedAssignments = computed(() => assignments.value.filter(a => a.completed));
const pendingAssignments = computed(() => assignments.value.filter(a => !a.completed));

// Get current/next class based on time
const currentClass = computed(() => {
  // This would normally use actual time comparison
  return todayClasses[1]; // Mock for demonstration
});
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
        <Card class="lg:col-span-1">
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

        <!-- Today's Schedule -->
        <Card class="lg:col-span-2">
          <CardHeader class="flex flex-row items-center justify-between">
            <CardTitle>Today's Schedule</CardTitle>
            <Button variant="ghost" size="sm" class="flex items-center">
              View Full Schedule
              <ChevronRightIcon class="w-4 h-4 ml-1" />
            </Button>
          </CardHeader>
          <CardContent>
            <div class="space-y-3 max-h-72 overflow-y-auto">
              <div v-for="(class_item, index) in todayClasses" :key="class_item.id"
                class="flex items-center p-3 border-l-4 rounded"
                :class="[index === 1 ? 'border-indigo-500' : 'border-gray-200']">
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
                    <div v-for="assignment in pendingAssignments" :key="assignment.id"
                      class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition">
                      <div class="mr-3">
                        <Checkbox :checked="assignment.completed" @update:checked="toggleCompletion(assignment.id)" />
                      </div>
                      <div class="flex-grow">
                        <div class="flex justify-between">
                          <h4 class="font-medium">{{ assignment.title }}</h4>
                          <Badge :class="getPriorityColor(assignment.priority)">{{ assignment.priority }}</Badge>
                        </div>
                        <div class="flex justify-between text-sm text-muted-foreground mt-1">
                          <span>{{ assignment.subject }}</span>
                          <span>Due: {{ assignment.dueDate }}</span>
                        </div>
                      </div>
                    </div>
                    <div v-if="pendingAssignments.length === 0" class="text-center py-4 text-muted-foreground">
                      No pending assignments!
                    </div>
                  </div>
                </TabsContent>
                <TabsContent value="completed">
                  <div class="space-y-3 max-h-80 overflow-y-auto">
                    <div v-for="assignment in completedAssignments" :key="assignment.id"
                         class="flex items-center p-3 border rounded-lg hover:bg-gray-50 transition">
                      <div class="mr-3">
                        <Checkbox :checked="assignment.completed" @update:checked="toggleCompletion(assignment.id)" />
                      </div>
                      <div class="flex-grow">
                        <div class="flex justify-between">
                          <h4 class="font-medium">{{ assignment.title }}</h4>
                          <Badge :class="getPriorityColor(assignment.priority)">{{ assignment.priority }}</Badge>
                        </div>
                        <div class="flex justify-between text-sm text-muted-foreground mt-1">
                          <span>{{ assignment.subject }}</span>
                          <span>Due: {{ assignment.dueDate }}</span>
                        </div>
                      </div>
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
                <div v-for="exam in exams" :key="exam.id"
                  class="p-4 border rounded-lg bg-gray-50 flex justify-between items-center">
                  <div>
                    <h4 class="font-bold">{{ exam.subject }} - {{ exam.topic }}</h4>
                    <p class="text-sm text-muted-foreground flex items-center mt-1">
                      <CalendarIcon class="w-4 h-4 mr-1" />
                      {{ exam.date }} at {{ exam.time }}
                    </p>
                  </div>
                  <div>
                    <Button variant="outline" size="sm">Study Resources</Button>
                  </div>
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
                <div v-for="grade in grades" :key="grade.id" class="p-3 border rounded-lg">
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
                <div v-for="announcement in announcements" :key="announcement.id" class="p-3 border-l-4 border-yellow-400 bg-yellow-50 rounded-r-lg">
                  <div class="flex justify-between">
                    <h4 class="font-bold">{{ announcement.title }}</h4>
                    <span class="text-xs text-muted-foreground">{{ announcement.date }}</span>
                  </div>
                  <p class="text-sm text-muted-foreground mt-1">{{ announcement.content }}</p>
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
                        <div v-for="resource in resources.Math" :key="resource.id" class="flex items-center p-2 border rounded-lg hover:bg-gray-50 transition">
                            <div class="mr-3 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            </div>
                            <div class="flex-grow">
                            <h4 class="font-medium text-gray-800">{{ resource.title }}</h4>
                            <span class="text-xs text-gray-500">{{ resource.type }}</span>
                            </div>
                            <Button variant="ghost" size = "icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            </Button>
                        </div>
                    </div>
                </TabsContent>
                <TabsContent value="science">
                    <!--  Science resources here -->
                </TabsContent>
                <TabsContent value="english">
                    <!-- English resources here -->
                </TabsContent>
                <TabsContent value="history">
                    <!--  History resources here -->
                </TabsContent>
              </Tabs>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
