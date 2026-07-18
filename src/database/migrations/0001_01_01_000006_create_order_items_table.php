<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('design_id')->constrained()->onDelete('restrict');
            $table->decimal('price', 12, 2);
            $table->json('customization_data')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('design_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
