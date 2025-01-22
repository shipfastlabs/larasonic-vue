<template>
  <div class="md:grid md:grid-cols-3 md:gap-6">
    <div class="flex justify-between md:col-span-1">
      <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium">
          <slot name="title" />
        </h3>

        <p class="mt-1 text-sm">
          <slot name="description" />
        </p>
      </div>

      <div class="px-4 sm:px-0">
        <slot name="aside" />
      </div>
    </div>

    <div class="mt-5 md:col-span-2 md:mt-0">
      <div class="border px-4 py-5 shadow-sm rounded-lg sm:p-6">
        <slot name="content" />
      </div>
    </div>
  </div>
</template>
