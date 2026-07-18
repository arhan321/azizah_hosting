<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('revisions');

        Schema::table('custom_orders', function (Blueprint $table) {
            if (Schema::hasColumn('custom_orders', 'no_revision')) {
                $table->dropColumn('no_revision');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('revision_number')->default(1);
            $table->text('notes');
            $table->enum('status', ['requested', 'in_progress', 'completed'])->default('requested');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('status');
        });

        Schema::table('custom_orders', function (Blueprint $table) {
            $table->boolean('no_revision')->default(false)->after('brief');
        });
    }
};
