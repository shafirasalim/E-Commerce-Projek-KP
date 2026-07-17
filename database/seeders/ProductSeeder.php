<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Kategori
        $electronics = Category::create([
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik dan gadget'
        ]);

        $fashion = Category::create([
            'name' => 'Fashion',
            'description' => 'Pakaian dan aksesoris'
        ]);

        $food = Category::create([
            'name' => 'Makanan',
            'description' => 'Makanan dan minuman'
        ]);

        // Buat Produk
        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Smartphone XYZ',
            'description' => 'Smartphone dengan kamera 48MP dan baterai 5000mAh',
            'price' => 3500000,
            'stock' => 10,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Laptop Gaming',
            'description' => 'Laptop gaming dengan RTX 3060 dan RAM 16GB',
            'price' => 12000000,
            'stock' => 5,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Kaos Polos Premium',
            'description' => 'Kaos katun combed 30s, nyaman dipakai',
            'price' => 85000,
            'stock' => 50,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name' => 'Celana Jeans',
            'description' => 'Celana jeans slim fit berkualitas',
            'price' => 250000,
            'stock' => 20,
            'status' => 'active',
        ]);

        Product::create([
            'category_id' => $food->id,
            'name' => 'Keripik Singkong',
            'description' => 'Keripik singkong balado pedas manis',
            'price' => 15000,
            'stock' => 100,
            'status' => 'active',
        ]);
    }
}