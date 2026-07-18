<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->string('material')->nullable()->after('name');
            $table->string('ornament_level')->nullable()->after('material');
            $table->decimal('width', 8, 2)->nullable()->after('ornament_level');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->string('address')->nullable()->after('color_preference');
            $table->boolean('no_revision')->default(false)->after('brief');
        });
    }

    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn(['material', 'ornament_level', 'width', 'height', 'address', 'no_revision']);
        });
    }
};
