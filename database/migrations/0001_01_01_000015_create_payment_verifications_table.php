<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('slip_image')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('slip_image')->nullable()->after('transaction_ref');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('slip_image');
        });
    }
};
