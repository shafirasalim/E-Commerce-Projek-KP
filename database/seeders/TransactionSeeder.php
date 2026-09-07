<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail; // Import model detail
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $users = User::whereHas('role', function($q) {
            $q->where('nama_role', 'customer');
        })->get();

        if ($users->isEmpty()) {
            $users = User::all();
        }

        $products = Product::all();
        
        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled', 'expired'];

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
                ];
            }

            $transactionDate = Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => $statuses[array_rand($statuses)],
                'transaction_date' => $transactionDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($itemsData as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}