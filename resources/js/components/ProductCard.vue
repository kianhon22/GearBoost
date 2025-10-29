<template>
  <div>
    <div 
      :class="[
        'bg-white rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full group cursor-pointer',
        product.stock <= 0 ? 'opacity-60' : ''
      ]"
      @click="goToProduct"
    >
      <div class="relative w-full pt-[75%] overflow-hidden bg-gray-100">
        <img 
          :src="`/storage/products/${product.image}`" 
          :alt="product.name" 
          :class="[
            'absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
            product.stock <= 0 ? 'grayscale' : ''
          ]"
        />
        <div 
          v-if="product.stock <= 0"
          class="absolute inset-0 flex items-center justify-center"
        >
          <div class="bg-red-500 text-white px-8 py-4 rounded-xl font-bold text-2xl">
            SOLD OUT
          </div>
        </div>
      </div>
      <div class="p-5 flex flex-col flex-grow">
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ product.name }}</h3>
        <p class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3">{{ product.description }}</p>
        <div class="flex justify-between items-center mt-auto">
          <div class="inline-flex items-baseline gap-1">
            <span class="font-semibold">RM {{ parseFloat(product.price).toFixed(2) }}</span>
          </div>
          <loading-button
            text="Add to Cart"
            loadingText="Adding..."
            :loading="adding"
            :disabled="product.stock <= 0"
            @click.stop="addToCart"
            variant="primary"
          />
        </div>
      </div>
    </div>

    <confirm-dialog
      :show="showConfirmDialog"
      title="Login Required"
      message="Please login to add products to cart."
      confirm-text="Login Now"
      cancel-text="Cancel"
      redirect-url="/login"
      @confirm="showConfirmDialog = false"
      @cancel="showConfirmDialog = false"
    />
  </div>
</template>

<script>
import { useNotification } from '../useNotification';

export default {
  name: 'ProductCard',
  props: {
    product: {
      type: Object,
      required: true
    },
    isAuthenticated: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      adding: false,
      showConfirmDialog: false
    };
  },
  methods: {
    goToProduct() {
      window.location.href = `/products/${this.product.id}`;
    },
    async addToCart() {
      if (this.adding) return;

      if (!this.isAuthenticated) {
        this.showConfirmDialog = true;
        return;
      }
      
      this.adding = true;
      const { showNotification } = useNotification();
      
      try {
        const response = await axios.post('/cart', {
          product_id: this.product.id,
          quantity: 1
        });

        if (response.data.success) {
          if (window.updateCartCount) {
            window.updateCartCount();
          }
          
          showNotification('Product added to cart!', 'success');
        } else {
          showNotification(response.data.message || 'Failed to add product to cart', 'error');
        }
      } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification(error.response?.data?.message || 'Failed to add product to cart', 'error');
      } finally {
        this.adding = false;
      }
    }
  }
};
</script>