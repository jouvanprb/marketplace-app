<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            'Mobile Legends' => 'Top up diamond dan beli akun sultan Mobile Legends.',
            'PUBG Mobile' => 'UC PUBG Mobile termurah dan aman.',
            'Valorant' => 'Valorant Points dan akun ranked siap main.',
            'Free Fire' => 'Top up FF cepat masuk tanpa login.',
            'Genshin Impact' => 'Genesis Crystals dan akun dengan karakter bintang 5.',
        ];

        $categoryIds = [];
        foreach (array_keys($categories) as $name) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $categoryIds[$name] = $cat->id;
        }

        // 2. Create Products (Top Up)
        $topupProducts = [
            // Mobile Legends
            ['category' => 'Mobile Legends', 'name' => '86 Diamonds', 'price' => 20000, 'stock' => 9999],
            ['category' => 'Mobile Legends', 'name' => '172 Diamonds', 'price' => 38000, 'stock' => 9999],
            ['category' => 'Mobile Legends', 'name' => '257 Diamonds + 27 Bonus', 'price' => 58000, 'stock' => 9999],
            ['category' => 'Mobile Legends', 'name' => 'Twilight Pass', 'price' => 140000, 'stock' => 999],
            
            // PUBG Mobile
            ['category' => 'PUBG Mobile', 'name' => '60 UC', 'price' => 15000, 'stock' => 9999],
            ['category' => 'PUBG Mobile', 'name' => '325 UC', 'price' => 75000, 'stock' => 9999],
            
            // Valorant
            ['category' => 'Valorant', 'name' => '420 VP', 'price' => 45000, 'stock' => 9999],
            ['category' => 'Valorant', 'name' => '1375 VP', 'price' => 140000, 'stock' => 9999],
            
            // Free Fire
            ['category' => 'Free Fire', 'name' => '140 Diamonds', 'price' => 20000, 'stock' => 9999],
            ['category' => 'Free Fire', 'name' => '355 Diamonds', 'price' => 50000, 'stock' => 9999],
            
            // Genshin Impact
            ['category' => 'Genshin Impact', 'name' => 'Blessing of the Welkin Moon', 'price' => 65000, 'stock' => 999],
            ['category' => 'Genshin Impact', 'name' => '980 Genesis Crystals', 'price' => 230000, 'stock' => 999],
        ];

        foreach ($topupProducts as $prod) {
            Product::updateOrCreate(
                [
                    'category_id' => $categoryIds[$prod['category']],
                    'name' => $prod['name'],
                ],
                [
                    'type' => 'topup',
                    'description' => 'Proses cepat 1-5 menit. Masukkan ID dan Server yang benar.',
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Create Products (Accounts)
        $accountProducts = [
            // Mobile Legends
            ['category' => 'Mobile Legends', 'name' => 'Akun Mythic Glory 600 Pts - Skin Collector Gusion', 'price' => 850000, 'stock' => 1],
            ['category' => 'Mobile Legends', 'name' => 'Akun Legend III - All Emblem Max', 'price' => 150000, 'stock' => 1],
            
            // Valorant
            ['category' => 'Valorant', 'name' => 'Akun Radiant Vandal Kuronami + Phantom Oni', 'price' => 1200000, 'stock' => 1],
            ['category' => 'Valorant', 'name' => 'Akun Smurf Iron 1 Full Agent', 'price' => 50000, 'stock' => 5],
            
            // Genshin Impact
            ['category' => 'Genshin Impact', 'name' => 'AR 58 - Raiden C2, Nahida, Kazuha (Asia)', 'price' => 1500000, 'stock' => 1],
            ['category' => 'Genshin Impact', 'name' => 'Starter Ayaka + Mistsplitter', 'price' => 120000, 'stock' => 1],
        ];

        foreach ($accountProducts as $prod) {
            Product::updateOrCreate(
                [
                    'category_id' => $categoryIds[$prod['category']],
                    'name' => $prod['name'],
                ],
                [
                    'type' => 'account',
                    'description' => 'Akun 100% aman (Anti Hackback). Detail login akan dikirimkan setelah pembayaran.',
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'is_active' => true,
                ]
            );
        }
    }
}
