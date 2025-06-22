<script setup>
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['close'])
</script>

<template>
  <Dialog :open="show" :class="[maxWidth ? `sm:max-w-${maxWidth}` : 'sm:max-w-2xl']" @update:open="emit('close')">
    <DialogContent>
      <DialogHeader>
        <div class="flex items-center gap-4">
          <DialogTitle>
            <slot name="title" />
          </DialogTitle>
        </div>
        <DialogDescription>
          <slot name="content" />
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <slot name="footer" />
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
