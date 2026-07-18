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
        Schema::table('payments', function (Blueprint $table) {
            // Fields untuk bank transfer
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_holder')->nullable()->after('account_number');
            $table->string('payment_proof')->nullable()->after('account_holder'); // foto bukti transfer
            $table->timestamp('transfer_date')->nullable()->after('payment_proof');

            // Fields untuk verifikasi admin
            $table->foreignId('verified_by')->nullable()->after('transfer_date')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'bank_name',
                'account_number',
                'account_holder',
                'payment_proof',
                'transfer_date',
                'verified_by',
                'verified_at',
                'verification_notes',
                'verification_status',
            ]);
        });
    }
};
