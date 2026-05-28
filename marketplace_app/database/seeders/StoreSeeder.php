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
            ['category' => 'Mobile Legends', 'name' => '86 Diamonds', 'price' => 20000, 'stock' => null],
            ['category' => 'Mobile Legends', 'name' => '172 Diamonds', 'price' => 38000, 'stock' => null],
            ['category' => 'Mobile Legends', 'name' => '257 Diamonds + 27 Bonus', 'price' => 58000, 'stock' => null],
            ['category' => 'Mobile Legends', 'name' => 'Twilight Pass', 'price' => 140000, 'stock' => null],
            
            // PUBG Mobile
            ['category' => 'PUBG Mobile', 'name' => '60 UC', 'price' => 15000, 'stock' => null],
            ['category' => 'PUBG Mobile', 'name' => '325 UC', 'price' => 75000, 'stock' => null],
            
            // Valorant
            ['category' => 'Valorant', 'name' => '420 VP', 'price' => 45000, 'stock' => null],
            ['category' => 'Valorant', 'name' => '1375 VP', 'price' => 140000, 'stock' => null],
            
            // Free Fire
            ['category' => 'Free Fire', 'name' => '140 Diamonds', 'price' => 20000, 'stock' => null],
            ['category' => 'Free Fire', 'name' => '355 Diamonds', 'price' => 50000, 'stock' => null],
            
            // Genshin Impact
            ['category' => 'Genshin Impact', 'name' => 'Blessing of the Welkin Moon', 'price' => 65000, 'stock' => null],
            ['category' => 'Genshin Impact', 'name' => '980 Genesis Crystals', 'price' => 230000, 'stock' => null],
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

        // 4. Create Dummy Customers
        $customers = [
            [
                'name' => 'Rian Wijaya',
                'email' => 'rian@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ],
        ];

        $customerModels = [];
        foreach ($customers as $c) {
            $customerModels[] = \App\Models\User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => $c['password'],
                    'role' => $c['role']
                ]
            );
        }

        // 5. Create Dummy Orders
        // Let's get some products to assign to orders
        $ml86 = Product::where('name', '86 Diamonds')->first();
        $ml172 = Product::where('name', '172 Diamonds')->first();
        $mlAcc = Product::where('name', 'Akun Mythic Glory 600 Pts - Skin Collector Gusion')->first();
        $valVP = Product::where('name', '1375 VP')->first();
        $valAcc = Product::where('name', 'Akun Radiant Vandal Kuronami + Phantom Oni')->first();
        $ff140 = Product::where('name', '140 Diamonds')->first();
        $pubg60 = Product::where('name', '60 UC')->first();

        $dummyOrders = [
            [
                'user' => $customerModels[0], // Rian
                'status' => 'paid',
                'payment_method' => 'QRIS',
                'items' => [
                    ['product' => $ml86, 'qty' => 2, 'game_account' => '12345678', 'user_id_game' => '1234'], // 20000 * 2 = 40000
                    ['product' => $ff140, 'qty' => 1, 'game_account' => '87654321', 'user_id_game' => null], // 20000 * 1 = 20000
                ]
            ],
            [
                'user' => $customerModels[1], // Siti
                'status' => 'paid',
                'payment_method' => 'Gopay',
                'items' => [
                    ['product' => $valVP, 'qty' => 1, 'game_account' => 'siti#123', 'user_id_game' => null], // 140000 * 1 = 140000
                    ['product' => $ml172, 'qty' => 3, 'game_account' => '987654321', 'user_id_game' => '4321'], // 38000 * 3 = 114000
                ]
            ],
            [
                'user' => $customerModels[2], // Budi
                'status' => 'paid',
                'payment_method' => 'Bank Transfer',
                'items' => [
                    ['product' => $mlAcc, 'qty' => 1, 'game_account' => null, 'user_id_game' => null], // 850000 * 1 = 850000
                ]
            ],
            [
                'user' => $customerModels[0], // Rian
                'status' => 'pending',
                'payment_method' => 'QRIS',
                'items' => [
                    ['product' => $valAcc, 'qty' => 1, 'game_account' => null, 'user_id_game' => null], // 1200000 * 1 = 1200000
                ]
            ],
            [
                'user' => $customerModels[1], // Siti
                'status' => 'expired',
                'payment_method' => 'OVO',
                'items' => [
                    ['product' => $pubg60, 'qty' => 1, 'game_account' => 'pubguser1', 'user_id_game' => null], // 15000 * 1 = 15000
                ]
            ]
        ];

        foreach ($dummyOrders as $index => $orderData) {
            $totalPrice = 0;
            foreach ($orderData['items'] as $item) {
                if ($item['product']) {
                    $totalPrice += $item['product']->price * $item['qty'];
                }
            }

            // Create Order
            $orderCode = 'TRX-' . strtoupper(Str::random(10));
            $order = \App\Models\Order::firstOrCreate(
                ['order_code' => $orderCode],
                [
                    'user_id' => $orderData['user']->id,
                    'total_price' => $totalPrice,
                    'status' => $orderData['status'],
                    'payment_method' => $orderData['payment_method'],
                    'payment_details' => json_encode(['payment_status' => $orderData['status']]),
                ]
            );

            // Create Order Items
            foreach ($orderData['items'] as $item) {
                if ($item['product']) {
                    \Illuminate\Support\Facades\DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'game_account' => $item['game_account'],
                        'user_id_game' => $item['user_id_game'],
                        'price' => $item['product']->price,
                        'quantity' => $item['qty'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
