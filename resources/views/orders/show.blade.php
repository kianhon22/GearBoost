@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - GearBoost')

@section('content')
<div id="app">
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('orders.index') }}" class="inline-block mb-8 text-purple-600 hover:text-purple-700 font-semibold transition-colors duration-200">
            ← Go Back
        </a>

        <div class="bg-white rounded-2xl p-12 shadow-xl">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-8 border-b-2 border-gray-200 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Order #{{ $order->id }}</h1>
                    <div class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="px-6 py-3 rounded-full font-bold {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                    {{ ucfirst($order->status) }}
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Items</h2>
                @foreach($order->items as $item)
                    <div class="flex flex-col md:flex-row items-center gap-6 p-6 bg-gray-50 rounded-xl mb-4">
                        <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-grow text-center md:text-left">
                            <div class="text-purple-600 font-semibold text-sm uppercase mb-1">{{ $item->product->category }}</div>
                            <div class="text-xl font-bold text-gray-900 mb-1">{{ $item->product->name }}</div>
                            <div class="text-gray-600">
                                Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}
                            </div>
                        </div>
                        <div class="text-2xl font-extrabold text-purple-600">
                            ${{ number_format($item->quantity * $item->price, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-purple-700 p-8 rounded-xl text-white">
                <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                <div class="flex justify-between mb-4 text-lg">
                    <span>Items ({{ $order->items->sum('quantity') }}):</span>
                    <span>RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mb-4 text-lg">
                    <span>Shipping:</span>
                    <span>RM 0.00</span>
                </div>
                <div class="flex justify-between mt-4 pt-4 border-t-2 border-white/30 text-3xl font-extrabold">
                    <span>Total:</span>
                    <span>RM {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
