<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
            $table->string('file_url', 500);
            $table->string('download_token', 100)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();

            $table->index('download_token');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_results');
    }
};
