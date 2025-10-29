<template>
  <teleport to="body">
    <div 
      v-if="show" 
      :class="notificationClasses"
      class="fixed top-5 right-5 px-6 py-4 rounded-lg font-semibold shadow-xl z-[9999] transition-all transform"
    >
      {{ message }}
    </div>
  </teleport>
</template>

<script>
export default {
  name: 'Notification',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'success',
      validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    }
  },
  computed: {
    notificationClasses() {
      const types = {
        success: 'bg-green-100 text-green-800 border border-green-200',
        error: 'bg-red-100 text-red-800 border border-red-200',
        warning: 'bg-yellow-100 text-yellow-800 border border-yellow-200',
        info: 'bg-blue-100 text-blue-800 border border-blue-200'
      };
      return types[this.type] || types.success;
    }
  }
};
</script>

