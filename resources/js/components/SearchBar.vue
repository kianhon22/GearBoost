<template>
  <div class="relative w-full max-w-[700px] mx-auto mb-8">
    <div class="flex items-center bg-white rounded-full shadow-md focus-within:shadow-purple-300 transition-shadow duration-300">
      <input
        type="text"
        @input="$emit('search', $event.target.value)"
        placeholder="🔍 Search for sports products..."
        class="flex-1 px-6 py-3 text-base text-gray-700 placeholder-gray-400 bg-transparent border-none focus:outline-none focus:ring-0"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',
};
</script>
