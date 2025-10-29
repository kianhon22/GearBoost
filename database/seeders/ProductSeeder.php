<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nike Air Zoom',
                'description' => 'Premium running Footwear with responsive cushioning and breathable mesh upper. Perfect for daily training and long-distance runs.',
                'price' => 129.99,
                'stock' => 50,
                'category' => 'footwear',
                'image' => 'running-shoes.jpg',
            ],
            [
                'name' => 'Adidas Predator Elite',
                'description' => 'Professional football boots with enhanced ball control and precision strike zone for optimal performance.',
                'price' => 249.99,
                'stock' => 30,
                'category' => 'footwear',
                'image' => 'football-boots.jpg',
            ],
            [
                'name' => 'Wilson Pro Tennis Racket',
                'description' => 'Professional-grade tennis racket with precision control and powerful swing. Ideal for advanced players.',
                'price' => 199.99,
                'stock' => 25,
                'category' => 'equipments',
                'image' => 'tennis-racket.jpg',
            ],
            [
                'name' => 'NBA Official Basketball',
                'description' => 'Official size and weight basketball with superior grip and durability. Perfect for indoor and outdoor play.',
                'price' => 49.99,
                'stock' => 100,
                'category' => 'equipments',
                'image' => 'basketball.jpg',
            ],
            [
                'name' => 'Under Armour Compression Shirt',
                'description' => 'High-performance compression shirt with moisture-wicking technology. Keeps you cool and dry during intense workouts.',
                'price' => 39.99,
                'stock' => 0,
                'category' => 'clothes',
                'image' => 'compression-shirt.jpg',
            ],
            [
                'name' => 'Champion Athletic Shorts',
                'description' => 'Lightweight and breathable athletic shorts with elastic waistband and side pockets. Perfect for any sport.',
                'price' => 29.99,
                'stock' => 80,
                'category' => 'clothes',
                'image' => 'athletic-shorts.jpg',
            ],
            [
                'name' => 'TRX Suspension Training Kit',
                'description' => 'Complete bodyweight training system for building strength, balance, and flexibility anywhere.',
                'price' => 179.99,
                'stock' => 40,
                'category' => 'equipments',
                'image' => 'trx-kit.jpg',
            ],
            [
                'name' => 'Bowflex Adjustable Dumbbells',
                'description' => 'Space-saving adjustable dumbbells with weight range from 5 to 52.5 lbs. Perfect for home workouts.',
                'price' => 239.99,
                'stock' => 0,
                'category' => 'equipments',
                'image' => 'dumbbells.jpg',
            ],
            [
                'name' => 'Yoga Mat Pro Series',
                'description' => 'Extra-thick, non-slip yoga mat with superior cushioning and eco-friendly materials.',
                'price' => 59.99,
                'stock' => 60,
                'category' => 'equipments',
                'image' => 'yoga-mat.jpg',
            ],
            [
                'name' => 'Garmin Forerunner 255',
                'description' => 'Advanced GPS running watch with heart rate monitoring, training metrics, and long battery life.',
                'price' => 349.99,
                'stock' => 35,
                'category' => 'accessories',
                'image' => 'smartwatch.jpg',
            ],
            [
                'name' => 'Hydro Flask Bottle 32oz',
                'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours or hot for 12 hours.',
                'price' => 44.99,
                'stock' => 90,
                'category' => 'accessories',
                'image' => 'bottle.jpg',
            ],
            [
                'name' => 'Callaway Golf Club Set',
                'description' => 'Complete 12-piece golf club set for beginners and intermediate players. Includes driver, irons, and putter.',
                'price' => 499.99,
                'stock' => 15,
                'category' => 'equipments',
                'image' => 'golf-clubs.jpg',
            ],
            [
                'name' => 'Puma Training Backpack',
                'description' => 'Spacious sports backpack with multiple compartments, water-resistant material, and comfortable straps.',
                'price' => 69.99,
                'stock' => 55,
                'category' => 'accessories',
                'image' => 'backpack.jpg',
            ],
            [
                'name' => 'Fitbit Charge',
                'description' => 'Advanced fitness tracker with built-in GPS, heart rate monitoring, and stress management tools.',
                'price' => 149.99,
                'stock' => 45,
                'category' => 'accessories',
                'image' => 'fitness-tracker.jpg',
            ],
            [
                'name' => 'Reebok CrossFit Training Footwear',
                'description' => 'Versatile training Footwear designed for CrossFit workouts with stability and flexibility.',
                'price' => 119.99,
                'stock' => 40,
                'category' => 'footwear',
                'image' => 'training-shoes.jpg',
            ],
            [
                'name' => 'Everlast Boxing Gloves',
                'description' => 'Professional-quality boxing gloves with premium leather and superior padding for protection.',
                'price' => 89.99,
                'stock' => 50,
                'category' => 'equipments',
                'image' => 'boxing-gloves.jpg',
            ],
            [
                'name' => 'Arena Swim Goggles',
                'description' => 'Professional swim goggles with anti-fog coating and UV protection. Perfect for competitive swimming.',
                'price' => 34.99,
                'stock' => 70,
                'category' => 'accessories',
                'image' => 'swim-goggles.jpg',
            ],
            [
                'name' => 'Nike Dri-FIT Training Jacket',
                'description' => 'Moisture-wicking training jacket with zippered pockets and breathable fabric for all-season wear.',
                'price' => 79.99,
                'stock' => 65,
                'category' => 'clothes',
                'image' => 'training-jacket.jpg',
            ],
            [
                'name' => 'Resistance Bands Set',
                'description' => 'Complete set of 5 resistance bands with different resistance levels for strength training and rehabilitation.',
                'price' => 24.99,
                'stock' => 100,
                'category' => 'equipments',
                'image' => 'resistance-bands.jpg',
            ],
            [
                'name' => 'Oakley Sport Sunglasses',
                'description' => 'High-performance sport sunglasses with polarized lenses and impact-resistant frames.',
                'price' => 159.99,
                'stock' => 30,
                'category' => 'accessories',
                'image' => 'sunglasses.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}