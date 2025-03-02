<script setup>
import AppSidebarContent from '@/Components/AppSidebarContent.vue'
import AppTeamManager from '@/Components/AppTeamManager.vue'
import AppUserManager from '@/Components/AppUserManager.vue'
import Breadcrumb from '@/Components/shadcn/ui/breadcrumb/Breadcrumb.vue'
import BreadcrumbItem from '@/Components/shadcn/ui/breadcrumb/BreadcrumbItem.vue'
import BreadcrumbLink from '@/Components/shadcn/ui/breadcrumb/BreadcrumbLink.vue'
import BreadcrumbList from '@/Components/shadcn/ui/breadcrumb/BreadcrumbList.vue'
import Separator from '@/Components/shadcn/ui/separator/Separator.vue'
import { Sidebar, SidebarFooter, SidebarHeader, SidebarInset } from '@/Components/shadcn/ui/sidebar'
import SidebarMenu from '@/Components/shadcn/ui/sidebar/SidebarMenu.vue'
import SidebarMenuItem from '@/Components/shadcn/ui/sidebar/SidebarMenuItem.vue'
import SidebarProvider from '@/Components/shadcn/ui/sidebar/SidebarProvider.vue'
import SidebarTrigger from '@/Components/shadcn/ui/sidebar/SidebarTrigger.vue'
import Sonner from '@/Components/shadcn/ui/sonner/Sonner.vue'
import { useSeoMetaTags } from '@/Composables/useSeoMetaTags.js'
import { onMounted, ref } from 'vue';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  title: String,
})

useSeoMetaTags({
  title: props.title,
})

// Page load time
const loadTime = ref(null);

onMounted(() => {
    // Ensure this runs only in the browser
    if (typeof window !== 'undefined' && window.performance) {
        const timing = window.performance.timing;
        loadTime.value = (timing.loadEventEnd - timing.navigationStart) / 1000; // in seconds
    }
});

</script>

<template>
  <div>
    <Sonner position="top-center" />
    <SidebarProvider>
      <Sidebar collapsible="icon">
        <SidebarHeader>
          <SidebarMenu>
            <SidebarMenuItem>
              <AppTeamManager v-if="$page.props.jetstream.hasTeamFeatures" />
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarHeader>

        <AppSidebarContent />

        <SidebarFooter>
          <SidebarMenu>
            <SidebarMenuItem>
              <AppUserManager />
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarFooter>
      </Sidebar>

      <SidebarInset>
        <header
          class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
        >
          <div class="flex items-center gap-2 px-4">
            <SidebarTrigger class="-ml-1" />
            <Separator orientation="vertical" class="mr-2 h-4 hidden md:block" />
            <Breadcrumb>
              <BreadcrumbList>
                <BreadcrumbItem>
                  <BreadcrumbLink>
                    {{ title }}
                  </BreadcrumbLink>
                </BreadcrumbItem>
              </BreadcrumbList>
            </Breadcrumb>
          </div>
        </header>
        <main class="flex flex-1 flex-col gap-4 p-4 pt-0">
          <slot />
        </main>

        <!-- Compact Footer -->
        <footer class="py-3 px-4 border-t">
          <div class="container mx-auto flex flex-wrap items-center justify-between gap-y-4">
            <!-- Copyright -->
            <div class="text-center md:text-left">
              <p class="text-sm text-muted-foreground">
                &copy; {{ new Date().getFullYear() }} DccpHub. All rights reserved.
              </p>
            </div>

            <!-- Social Links -->
            <div class="flex items-center gap-3">
              <Link href="https://github.com/yukazakiri" class="text-muted-foreground hover:text-primary transition-colors" target="_blank">
                <Icon icon="lucide:github" class="h-5 w-5" />
              </Link>
              <Link href="#" class="text-muted-foreground hover:text-primary transition-colors" target="_blank">
                <Icon icon="lucide:twitter" class="h-5 w-5" />
              </Link>
            </div>

            <!-- Tech Stack & Load Time -->
            <div class="flex items-center gap-2 text-muted-foreground text-sm">
              <Icon icon="logos:laravel" class="h-4 w-4" />
              <Icon icon="logos:vue" class="h-4 w-4" />
              <Icon icon="logos:inertiajs-icon" class="h-4 w-4" />
              <Icon icon="logos:tailwindcss-icon" class="h-4 w-4" />
              <Icon icon="logos:vitejs" class="h-4 w-4" />
              <span v-if="loadTime" class="text-xs">
                ({{ loadTime }}s)
              </span>
              <span v-else class="text-xs">
                (Loading...)
              </span>
            </div>
          </div>
        </footer>

      </SidebarInset>
    </SidebarProvider>
  </div>
</template>
