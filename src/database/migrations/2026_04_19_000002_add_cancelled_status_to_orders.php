<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify enum to include 'cancelled'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'approved', 'dikerjakan', 'selesai', 'cancelled') DEFAULT 'pending'");
        } else {
            // For other databases like SQLite, enum handling may differ
            Schema::table('orders', function (Blueprint $table) {
                // SQLite doesn't support enum, so this is a no-op gracefully
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'approved', 'dikerjakan', 'selesai') DEFAULT 'pending'");
        }
    }
};
