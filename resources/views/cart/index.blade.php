@extends('layouts.app')

@section('title', 'Shopping Cart - GearBoost')

@section('content')
<div id="app">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Your Cart</h1>

        @if($cart && $cart->items->count() > 0)
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="selectAll"
                                onchange="toggleSelectAll()"
                                class="w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500"
                            />
                            <span class="font-semibold text-gray-700">Select All (<span id="items-count">{{ $cart->items->count() }}</span> items)</span>
                        </label>
                        <button 
                            onclick="clearCart()"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 hover:cursor-pointer text-white rounded-lg font-medium transition"
                        >
                            Clear All
                        </button>
                    </div>

                    <!-- Cart Items List -->
                    @foreach($cart->items as $item)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden" data-item-id="{{ $item->id }}">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-start gap-4">
                                <!-- Checkbox -->
                                <input 
                                    type="checkbox" 
                                    class="item-checkbox mt-2 w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500"
                                    value="{{ $item->id }}"
                                    onchange="updateSelectedCount()"
                                />
                                
                                <!-- CartItem Component -->
                                <div class="flex-grow">
                                    <cart-item :item='@json($item)'></cart-item>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal:</span>
                                <span class="font-semibold" id="subtotal">RM {{ number_format($cart->getTotalAmount(), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax (0%):</span>
                                <span class="font-semibold">RM 0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping:</span>
                                <span class="font-semibold">RM 0.00</span>
                            </div>
                            <div class="border-t pt-4 flex justify-between text-xl font-bold text-gray-900">
                                <span>Total:</span>
                                <span id="total">RM {{ number_format($cart->getTotalAmount(), 2) }}</span>
                            </div>
                            <div class="text-sm text-gray-500" id="selected-info">
                                <span id="selected-count">0</span> item(s) selected
                            </div>
                        </div>

                        <button 
                            onclick="checkout()" 
                            id="checkoutBtn"
                            class="w-full py-4 bg-purple-700 text-white rounded-xl hover:cursor-pointer font-bold text-lg hover:-translate-y-1 hover:shadow-2xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        >
                            Proceed to Checkout
                        </button>
                        
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-purple-600 hover:text-purple-700 font-semibold">
                            ← Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <empty-state
                icon="🛒"
                title="Your cart is empty"
                message="Looks like you haven't added any items to your cart yet. Start shopping to fill it up!"
            >
                <template #action>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-700 text-white rounded-xl font-bold hover:-translate-y-1 hover:shadow-2xl transition-all duration-200">
                        Start Shopping
                    </a>
                </template>
            </empty-state>
        @endif
    </div>
</div>

<script>
    let selectedItems = [];

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
            selectedItems.push(checkbox.value);
        });
        document.getElementById('selectAll').checked = true;
        updateSelectedCount();
    });

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        selectedItems = [];
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
            if (selectAll.checked) {
                selectedItems.push(checkbox.value);
            }
        });
        
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        selectedItems = [];
        
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedItems.push(checkbox.value);
            }
        });
        
        const selectAll = document.getElementById('selectAll');
        selectAll.checked = selectedItems.length === checkboxes.length && checkboxes.length > 0;
        
        document.getElementById('selected-count').textContent = selectedItems.length;
        
        // Enable/disable checkout button
        const checkoutBtn = document.getElementById('checkoutBtn');
        checkoutBtn.disabled = selectedItems.length === 0;
    }

    function clearCart() {
        showCustomConfirm(
            'Clear Cart',
            'Are you sure you want to clear your entire cart?',
            async function() {
                try {
                    const response = await axios.post('/cart/clear');
                    
                    if (response.data.success) {
                        window.showNotification('Cart cleared successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    window.showNotification(error.response?.data?.message || 'Failed to clear cart', 'error');
                }
            }
        );
    }

    async function checkout() {
        if (selectedItems.length === 0) {
            window.showNotification('Please select items to checkout', 'error');
            return;
        }

        const btn = document.getElementById('checkoutBtn');
        const originalText = btn.textContent;
        
        btn.disabled = true;
        btn.textContent = 'Processing...';

        try {
            const response = await axios.post('/orders', {
                selected_items: selectedItems
            });
            
            if (response.data.success) {
                window.showNotification('Order placed successfully!', 'success');
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
                setTimeout(() => {
                    window.location.href = response.data.redirect;
                }, 1000);
            } else {
                window.showNotification(response.data.message || 'Failed to place order', 'error');
                
                if (response.data.redirect) {
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 2000);
                } else {
                    btn.disabled = false;
                    btn.textContent = originalText;
                }
            }
        } catch (error) {
            console.error('Error:', error);
            window.showNotification(error.response?.data?.message || 'Failed to place order', 'error');
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }
</script>
@endsection
