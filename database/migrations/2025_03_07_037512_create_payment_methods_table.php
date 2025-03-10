<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['cash', 'bank_transfer', 'e_wallet']);
            $table->string('provider')->nullable(); // Bank name or E-wallet provider
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update payments table to use payment_method_id
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
            $table->string('payment_method')->nullable();
        });
        
        Schema::dropIfExists('payment_methods');
    }
};
