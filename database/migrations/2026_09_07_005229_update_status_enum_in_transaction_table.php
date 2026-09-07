<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum status di tabel transactions agar menerima 'shipped' dan 'completed'
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'paid', 'shipped', 'completed', 'cancelled', 'expired') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke kondisi semula jika di-rollback
        DB::statement("ALTER TABLE `transactions` MODIFY COLUMN `status` ENUM('pending', 'paid', 'cancelled', 'expired') DEFAULT 'pending'");
    }
};