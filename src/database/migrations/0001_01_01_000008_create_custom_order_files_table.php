<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_order_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_order_id')->constrained()->onDelete('cascade');
            $table->string('file_url', 500);
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();

            $table->index('custom_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_order_files');
    }
};
