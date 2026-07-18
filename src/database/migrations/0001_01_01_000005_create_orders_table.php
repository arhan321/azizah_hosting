<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('order_type', ['catalog', 'custom'])->default('catalog');
            $table->enum('status', ['pending', 'approved', 'dikerjakan', 'selesai'])->default('pending');
            $table->decimal('total_price', 12, 2);
            $table->string('payment_method')->nullable();
            $table->enum('payment_type', ['full', 'dp'])->default('full');
            $table->enum('payment_status', ['unpaid', 'dp_paid', 'fully_paid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
            $table->index('order_type');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
