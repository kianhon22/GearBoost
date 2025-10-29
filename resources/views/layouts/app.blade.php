<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GearBoost - Premium Sports Products')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-['Inter'] bg-gray-50 text-gray-900">
    <nav class="bg-purple-700 py-4 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="{{ route('products.index') }}" class="flex items-center gap-2 text-white text-2xl font-extrabold hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.png') }}" alt="GearBoost Logo" class="w-10 h-10 object-contain">
                <span>GearBoost</span>
            </a>
            <div class="flex flex-wrap items-center gap-8 justify-center">
                <a href="{{ route('products.index') }}" class="text-white font-medium hover:opacity-80 transition-opacity flex items-center gap-2">🛍️ Products</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="text-white font-medium hover:opacity-80 transition-opacity flex items-center gap-2">📦 My Orders</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white font-medium hover:opacity-80 transition-opacity cursor-pointer">🚪 Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white font-medium hover:opacity-80 transition-opacity flex items-center gap-2">🔐 Login</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="text-white font-medium hover:opacity-80 transition-opacity flex items-center gap-2 relative">
                    🛒 Cart
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold" id="cart-count">0</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto my-8 px-8">
        @if(session('success'))
            <div class="p-4 mb-4 bg-green-100 text-green-800 border border-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 bg-red-100 text-red-800 border border-red-200 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        async function updateCartCount() {
            try {
                const response = await axios.post('{{ route("cart.count") }}');
                document.getElementById('cart-count').textContent = response.data.count;
            } catch (error) {
                console.error('Error fetching cart count:', error);
            }
        }

        window.updateCartCount = updateCartCount;
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateCartCount);
        } else {
            updateCartCount();
        }
    </script>
</body>
</html>
