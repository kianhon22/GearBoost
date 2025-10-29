<template>
  <span :class="badgeClasses">
    {{ statusText }}
  </span>
</template>

<script>
export default {
  name: 'StatusBadge',
  props: {
    status: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'order', // 'order', 'stock', 'category'
    }
  },
  computed: {
    statusText() {
      if (this.type === 'stock') {
        return this.status;
      }
      return this.status.charAt(0).toUpperCase() + this.status.slice(1);
    },
    badgeClasses() {
      const baseClasses = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium';
      
      if (this.type === 'category') {
        return `${baseClasses} bg-purple-700 text-white`;
      }
      
      if (this.type === 'stock') {
        if (this.status === 'Out of Stock') {
          return `${baseClasses} bg-red-100 text-red-800`;
        } else if (this.status === 'Low Stock') {
          return `${baseClasses} bg-yellow-100 text-yellow-800`;
        }
        return `${baseClasses} bg-green-100 text-green-800`;
      }
      
      const statusColors = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
      };
      
      return `${baseClasses} ${statusColors[this.status] || 'bg-gray-100 text-gray-800'}`;
    }
  }
};
</script>

