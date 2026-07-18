<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('specification')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('image_url', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
