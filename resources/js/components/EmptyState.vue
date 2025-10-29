<template>
  <div class="flex flex-col items-center justify-center py-16 px-4">
    <div class="text-6xl mb-4">{{ icon }}</div>
    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ title }}</h3>
    <p class="text-gray-600 text-center mb-6 max-w-md">{{ message }}</p>
    <slot name="action"></slot>
  </div>
</template>

<script>
export default {
  name: 'EmptyState',
  props: {
    icon: {
      type: String,
      default: '📦'
    },
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      required: true
    }
  }
};
</script>

