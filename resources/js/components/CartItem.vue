<template>
  <div>
    <div class="flex flex-col sm:flex-row gap-4 bg-white p-4 sm:p-6 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200">
      <img 
        :src="`/storage/products/${item.product.image}`" 
        :alt="item.product.name"        
        class="w-full sm:w-32 h-32 object-cover rounded-lg" 
      />
      <div class="flex-grow space-y-2">
        <h3 class="text-xl font-bold text-gray-900">{{ item.product.name }}</h3>
        <div class="inline-flex items-baseline gap-1">
          <span class="md font-semibold">RM {{ parseFloat(item.product.price).toFixed(2) }}</span>
        </div>
      </div>
      <div class="flex flex-col sm:items-end justify-between gap-4">
        <div class="h-10 flex items-center gap-2 bg-gray-100 rounded-lg p-2">
          <button 
            @click="decreaseQuantity" 
            class="w-6 h-6 flex items-center justify-center bg-white rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed font-bold text-gray-700 transition"
            :disabled="updating"
          >
            -
          </button>
          <input 
            type="number" 
            v-model.number="quantity" 
            @change="updateQuantity"
            min="1"
            :max="item.product.stock"
            class="w-16 text-center border-none bg-transparent font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded"
            :disabled="updating"
          />
          <button 
            @click="increaseQuantity" 
            class="w-6 h-6 flex items-center justify-center bg-white rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed font-bold text-gray-700 transition"
            :disabled="updating"
          >
            +
          </button>
        </div>
        <div class="flex items-center justify-between sm:flex-col sm:items-end gap-3">
          <div class="inline-flex items-baseline gap-1">
            <span class="text-lg text-purple-600 font-semibold">Total: RM {{ subtotal }}</span>
          </div>
          <button 
            @click="showConfirmDialog = true" 
            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white hover:cursor-pointer rounded-lg font-medium transition disabled:opacity-50"
            :disabled="removing"
          >
            {{ removing ? 'Removing...' : 'Remove' }}
          </button>
        </div>
      </div>
    </div>

    <confirm-dialog
      :show="showConfirmDialog"
      title="Remove Item"
      message="Are you sure you want to remove this item from cart?"
      confirm-text="Remove"
      cancel-text="Cancel"
      @confirm="confirmRemove"
      @cancel="showConfirmDialog = false"
    />
  </div>
</template>

<script>
import { useNotification } from '../useNotification';

export default {
  name: 'CartItem',
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      quantity: this.item.quantity,
      updating: false,
      removing: false,
      showConfirmDialog: false
    };
  },
  computed: {
    subtotal() {
      return (this.quantity * parseFloat(this.item.product.price)).toFixed(2);
    }
  },
  methods: {
    decreaseQuantity() {
      if (this.quantity > 1) {
        this.quantity--;
        this.updateQuantity();
      }
    },
    increaseQuantity() {
      if (this.quantity < this.item.product.stock) {
        this.quantity++;
        this.updateQuantity();
      }
    },
    async updateQuantity() {
      if (this.updating) return;
      
      const { showNotification } = useNotification();
      
      if (this.quantity === 0) {
        await this.removeItem();
        return;
      }

      if (this.quantity < 1) {
        this.quantity = 1;
        return;
      }

      if (this.quantity > this.item.product.stock) {
        showNotification(`Only ${this.item.product.stock} items available in stock`, 'error');
        this.quantity = this.item.product.stock;
        return;
      }

      this.updating = true;

      try {
        const response = await axios.put(`/cart/${this.item.id}`, {
          quantity: this.quantity
        });

        if (response.data.success) {
          if (response.data.removed) {
            this.$emit('removed', this.item.id);
          }
          
          if (window.updateCartCount) {
            window.updateCartCount();
          }
          
          showNotification(response.data.message, 'success');
        } else {
          showNotification(response.data.message || 'Failed to update cart', 'error');
          this.quantity = this.item.quantity;
        }
      } catch (error) {
        console.error('Error updating cart:', error);
        showNotification(error.response?.data?.message || 'Failed to update cart', 'error');
        this.quantity = this.item.quantity;
      } finally {
        this.updating = false;
      }
    },
    confirmRemove() {
      this.showConfirmDialog = false;
      this.removeItem();
    },
    async removeItem() {
      if (this.removing) return;

      const { showNotification } = useNotification();
      this.removing = true;

      try {
        const response = await axios.delete(`/cart/${this.item.id}`);        

        if (response.data.success) {
          showNotification('Item removed from cart', 'success');
          if (window.updateCartCount) {
            window.updateCartCount();
          }
          // Reload page after a short delay
          setTimeout(() => location.reload(), 500);
        } else {
          showNotification(response.data.message || 'Failed to remove item', 'error');
          this.removing = false;
        }
      } catch (error) {
        console.error('Error removing item:', error);
        showNotification(error.response?.data?.message || 'Failed to remove item', 'error');
        this.removing = false;
      }
    }
  }
};
</script>
