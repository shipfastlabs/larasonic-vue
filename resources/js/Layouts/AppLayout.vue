<script setup>
import AppSidebarContent from '@/componentsAppSidebarContent.vue'
import AppTeamManager from '@/componentsAppTeamManager.vue'
import AppUserManager from '@/componentsAppUserManager.vue'
import Breadcrumb from '@/componentsui/breadcrumb/Breadcrumb.vue'
import BreadcrumbItem from '@/componentsui/breadcrumb/BreadcrumbItem.vue'
import BreadcrumbLink from '@/componentsui/breadcrumb/BreadcrumbLink.vue'
import BreadcrumbList from '@/componentsui/breadcrumb/BreadcrumbList.vue'
import Separator from '@/componentsui/separator/Separator.vue'
import { Sidebar, SidebarFooter, SidebarHeader, SidebarInset } from '@/componentsui/sidebar'
import SidebarMenu from '@/componentsui/sidebar/SidebarMenu.vue'
import SidebarMenuItem from '@/componentsui/sidebar/SidebarMenuItem.vue'
import SidebarProvider from '@/componentsui/sidebar/SidebarProvider.vue'
import SidebarTrigger from '@/componentsui/sidebar/SidebarTrigger.vue'
import Sonner from '@/componentsui/sonner/Sonner.vue'
import { useSeoMetaTags } from '@/Composables/useSeoMetaTags.js'

const props = defineProps({
  title: String,
})

useSeoMetaTags({
  title: props.title,
})
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
      </SidebarInset>
    </SidebarProvider>
  </div>
</template>
