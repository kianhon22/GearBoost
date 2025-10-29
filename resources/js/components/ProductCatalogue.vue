<template>
  <div>
    <div class="text-center mb-12">
      <h1 class="text-4xl font-extrabold mb-2 bg-purple-700 bg-clip-text text-transparent">
        Premium Sports Equipment
      </h1>
      <p class="text-lg text-gray-600">Find the perfect gear to boost your performance</p>
    </div>

    <search-bar @search="handleSearch"></search-bar>

    <div class="bg-white p-6 rounded-xl shadow-md mb-8">
      <h3 class="font-bold text-gray-900 mb-4">Categories</h3>
      <div class="flex flex-wrap gap-3">
        <button 
          @click="filterByCategory(null)"
          :class="[
            'px-5 py-2.5 rounded-full font-semibold transition-all duration-200',
            selectedCategory === null 
              ? 'bg-purple-600 text-white border-2 border-purple-600' 
              : 'bg-white text-gray-700 border-2 border-gray-300 hover:border-purple-600 hover:bg-purple-600 hover:text-white'
          ]"
        >
          All Products
        </button>
        <button 
          v-for="category in categories" 
          :key="category"
          @click="filterByCategory(category)"
          :class="[
            'px-5 py-2.5 rounded-full font-semibold transition-all duration-200',
            selectedCategory === category 
              ? 'bg-purple-600 text-white border-2 border-purple-600' 
              : 'bg-white text-gray-700 border-2 border-gray-300 hover:border-purple-600 hover:bg-purple-600 hover:text-white'
          ]"
        >
        {{ category.charAt(0).toUpperCase() + category.slice(1) }}
        </button>
      </div>
    </div>

    <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
      <product-card 
        v-for="product in filteredProducts" 
        :key="product.id"
        :product="product"
        :is-authenticated="isAuthenticated"
      ></product-card>
    </div>
    <div v-else class="text-center py-16 bg-white rounded-xl shadow-md">
      <div class="text-6xl mb-4">🔍</div>
      <div class="text-2xl text-gray-600">No products found</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductCatalogue',
  props: {
    initialProducts: {
      type: Array,
      required: true
    },
    initialCategories: {
      type: Array,
      required: true
    },
    isAuthenticated: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      products: this.initialProducts,
      categories: this.initialCategories,
      selectedCategory: null,
      searchQuery: ''
    };
  },
  computed: {
    filteredProducts() {
      let filtered = this.products;

      if (this.selectedCategory) {
        filtered = filtered.filter(p => p.category === this.selectedCategory);
      }

      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(p => 
          p.name.toLowerCase().includes(query) ||
          p.description.toLowerCase().includes(query) ||
          p.category.toLowerCase().includes(query)
        );
      }

      return filtered;
    }
  },
  methods: {
    filterByCategory(category) {
      this.selectedCategory = category;
    },
    handleSearch(query) {
      this.searchQuery = query;
    }
  }
};
</script>



