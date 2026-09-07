<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Ambil user yang role-nya customer
        $users = User::whereHas('role', function($q) {
            $q->where('nama_role', 'customer');
        })->get();

        // Fallback kalau gak ada user customer
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $products = Product::all();
        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        $addresses = [
            'Jl. Raya Cianjur No. 12, Kec. Cianjur, Kab. Cianjur, Jawa Barat 43216',
            'Jl. Puncak Pass No. 88, Kec. Cisarua, Kab. Bogor, Jawa Barat 16750',
            'Jl. Asia Afrika No. 45, Kec. Sumur Bandung, Kota Bandung, Jawa Barat 40111',
            'Jl. Merdeka No. 10, RT 02/RW 05, Kec. Bogor Tengah, Kota Bogor, Jawa Barat 16128',
            'Jl. Sudirman No. 99, Kec. Menteng, Jakarta Pusat, DKI Jakarta 10310'
        ];

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $totalAmount = 0;
            
            // Random 1 sampai 3 produk per transaksi
            $selectedProducts = $products->random(min(3, $products->count()));
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 5);
                $subtotal = $product->price * $qty;
                $totalAmount += $subtotal;
                
                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Buat Transaksi
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'invoice_id' => 'INV-' . strtoupper(Str::random(8)),
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => $totalAmount,
                'shipping_address' => $addresses[array_rand($addresses)],
                'payment_method' => 'bank_transfer',
                'created_at' => now()->subDays(rand(1, 30)), // Random tanggal 30 hari terakhir
                'updated_at' => now(),
            ]);

            // Masukkan item transaksi (Pastikan relasi di Model Transaction bernama 'items')
            // Kalau di model lu namanya 'transactionItems', ganti $transaction->items() jadi $transaction->transactionItems()
            $transaction->items()->createMany($itemsData);
        }
    }
}