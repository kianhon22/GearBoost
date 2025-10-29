@extends('layouts.app')

@section('title', 'My Orders - GearBoost')

@section('content')
<div id="app">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8">📦 My Orders</h1>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b-2 border-gray-200 gap-4">
                        <div>
                            <div class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</div>
                            <div class="text-gray-600 text-sm">Placed on {{ $order->created_at->format('F j, Y') }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-2 rounded-full font-semibold text-sm {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>                            
                        </div>
                    </div>

                    <div class="mb-6 space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-grow">
                                    <div class="font-semibold text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</div>
                                </div>
                                <div class="font-bold text-purple-600">RM {{ number_format($item->price * $item->quantity, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="text-2xl font-extrabold text-gray-900">Total: RM {{ number_format($order->total_amount, 2) }}</div>
                            <div class="flex gap-3 w-full sm:w-auto sm:justify-end">
                                @if($order->status !== 'cancelled' && $order->status !== 'completed')
                                    <button 
                                        onclick="cancelOrder('{{ $order->id }}')"
                                        class="px-4 py-2 bg-red-500 hover:bg-red-600 hover:cursor-pointer text-white rounded-lg font-semibold text-sm transition-colors"
                                    >
                                        Cancel Order
                                    </button>
                                @endif
                            <a href="{{ route('orders.show', $order) }}" class="w-full sm:w-auto text-sm px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 duration-200">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <empty-state
                icon="📦"
                title="You haven't placed any orders yet"
                message="Start shopping to see your orders here"
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
    function cancelOrder(orderId) {
        showCustomConfirm(
            'Cancel Order',
            'Are you sure you want to cancel this order?',
            async function() {
                try {
                    const response = await axios.put(`/orders/${orderId}`, {
                        status: 'cancelled'
                    });

                    if (response.data.success) {
                        window.showNotification('Order cancelled successfully', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        window.showNotification(response.data.message || 'Failed to cancel order', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    window.showNotification(error.response?.data?.message || 'Failed to cancel order', 'error');
                }
            }
        );
    }
</script>
@endsection
