<script setup>
import { cn } from '@/lib/utils'
import { useEventListener, useMediaQuery, useVModel } from '@vueuse/core'
import { TooltipProvider } from 'radix-vue'
import { computed, ref } from 'vue'
import { provideSidebarContext, SIDEBAR_COOKIE_MAX_AGE, SIDEBAR_COOKIE_NAME, SIDEBAR_KEYBOARD_SHORTCUT, SIDEBAR_WIDTH, SIDEBAR_WIDTH_ICON } from './utils'

const props = defineProps({
  defaultOpen: { type: Boolean, default: true },
  open: { type: Boolean, default: undefined },
  class: { type: null, required: false }
})

const emits = defineEmits(['update:open'])

const isMobile = useMediaQuery('(max-width: 768px)')
const openMobile = ref(false)

const open = useVModel(props, 'open', emits, {
  defaultValue: props.defaultOpen ?? false,
  passive: props.open === undefined
})

function setOpen(value) {
  open.value = value
  document.cookie = `${SIDEBAR_COOKIE_NAME}=${open.value}; path=/; max-age=${SIDEBAR_COOKIE_MAX_AGE}`
}

function setOpenMobile(value) {
  openMobile.value = value
}

function toggleSidebar() {
  return isMobile.value ? setOpenMobile(!openMobile.value) : setOpen(!open.value)
}

useEventListener('keydown', (event) => {
  if (event.key === SIDEBAR_KEYBOARD_SHORTCUT && (event.metaKey || event.ctrlKey)) {
    event.preventDefault()
    toggleSidebar()
  }
})

const state = computed(() => open.value ? 'expanded' : 'collapsed')

provideSidebarContext({
  state,
  open,
  setOpen,
  isMobile,
  openMobile,
  setOpenMobile,
  toggleSidebar,
})
</script>

<template>
  <TooltipProvider :delay-duration="0">
    <div
      :style="{
        '--sidebar-width': SIDEBAR_WIDTH,
        '--sidebar-width-icon': SIDEBAR_WIDTH_ICON,
      }"
      :class="cn('group/sidebar-wrapper flex min-h-svh w-full text-sidebar-foreground has-data-[variant=inset]:bg-sidebar', props.class)"
    >
      <slot />
    </div>
  </TooltipProvider>
</template>
