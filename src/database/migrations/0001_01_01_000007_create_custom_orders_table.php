<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('dimensions')->nullable();
            $table->string('color_preference')->nullable();
            $table->date('deadline')->nullable();
            $table->text('brief')->nullable();
            $table->decimal('admin_quote', 12, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
