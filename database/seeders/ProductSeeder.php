<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada kategori, kalau tidak ada buat dummy
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $cat1 = Category::create(['name' => 'Makanan']);
            $cat2 = Category::create(['name' => 'Minuman']);
            $categories = collect([$cat1, $cat2]);
        }

        $productNames = [
            'Kopi Arabika Cianjur', 'Teh Hijau Premium', 'Madu Hutan Asli', 'Beras Merah Organik',
            'Gula Aren Cair', 'Keripik Singkong', 'Dodol Garut', 'Sirup Markisa',
            'Selai Nanas', 'Kopi Robusta', 'Teh Celup Melati', 'Madu Murni 500ml',
            'Beras Putih Premium', 'Gula Semut Aren', 'Keripik Pisang', 'Sale Pisang',
            'Sirup Sirsak', 'Selai Stroberi', 'Kopi Luwak', 'Teh Oolong',
            'Madu Kelengkeng', 'Beras Coklat', 'Gula Batu', 'Keripik Tempe',
            'Dodol Kolang Kaling', 'Sirup Mangga', 'Selai Blueberry', 'Kopi Toraja',
            'Teh Putih', 'Madu Trigona'
        ];

        foreach ($productNames as $index => $name) {
            Product::create([
                'name' => $name,
                'category_id' => $categories->random()->id,
                'price' => rand(15000, 150000),
                'stock' => rand(10, 100),
                'status' => 'active',
                'description' => 'Deskripsi dummy untuk produk ' . $name . '. Produk berkualitas tinggi langsung dari petani lokal Cianjur.',
                'image' => null, // Kosongkan dulu karena kita gak punya file gambar asli
            ]);
        }
    }
}