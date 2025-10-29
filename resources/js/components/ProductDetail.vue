<template>
  <div>
    <div class="bg-white rounded-2xl p-8 md:p-12 shadow-xl">
      <div class="grid md:grid-cols-2 gap-12 mb-8">
        <div>
          <img :src="`/storage/products/${product.image}`" :alt="product.name" class="w-full aspect-square object-cover rounded-xl shadow-md">
        </div>
        <div class="flex flex-col">
          <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">{{ product.name }}</h1>
          <div class="text-3xl font-bold bg-purple-700 bg-clip-text text-transparent mb-6">
            RM {{ parseFloat(product.price).toFixed(2) }}
          </div>
          <p class="text-lg leading-relaxed text-gray-600 mb-8">{{ product.description }}</p>

          <div v-if="product.stock > 0">
            <div :class="['p-4 rounded-lg mb-8 font-semibold', product.stock < 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800']">
              <span v-if="product.stock < 10">⚠️ Only {{ product.stock }} left in stock!</span>
              <span v-else>In Stock ({{ product.stock }} available)</span>
            </div>

            <div class="flex items-center gap-4 mb-8">
              <label class="font-semibold text-gray-700">Quantity:</label>
              <input 
                type="number" 
                v-model.number="quantity"
                class="w-24 px-4 py-3 border-2 border-gray-300 rounded-lg text-center font-semibold focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                min="1" 
                :max="product.stock"
              >
            </div>

            <loading-button
              text="Add to Cart"
              loadingText="Adding to Cart..."
              :loading="adding"
              @click="addToCart"
              variant="primary"
              class="w-full text-lg py-4"
            />
          </div>
          <div v-else>
            <button disabled class="w-full py-4 bg-gray-400 text-white rounded-xl font-bold text-lg cursor-not-allowed">
              SOLD OUT
            </button>
          </div>
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
import axios from 'axios';
import { useNotification } from '../useNotification';

export default {
  name: 'ProductDetail',
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
      quantity: 1,
      adding: false,
      showConfirmDialog: false
    };
  },
  methods: {
    async addToCart() {
      if (this.adding) return;

      if (!this.isAuthenticated) {
        this.showConfirmDialog = true;
        return;
      }

      const { showNotification } = useNotification();

      if (this.quantity > this.product.stock) {
        showNotification('Requested quantity exceeds available stock', 'error');
        return;
      }

      this.adding = true;

      try {
        const response = await axios.post('/cart', {
          product_id: this.product.id,
          quantity: this.quantity
        });

        if (response.data.success) {
          if (window.updateCartCount) {
            window.updateCartCount();
          }
          
          showNotification(response.data.message, 'success');
          this.quantity = 1;
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

