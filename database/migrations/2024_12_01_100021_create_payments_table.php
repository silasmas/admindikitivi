<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 45)->nullable();
            $table->string('provider_reference', 45)->nullable();
            $table->text('order_number')->nullable();
            $table->decimal('amount', 9, 2)->nullable();
            $table->decimal('amount_customer', 9, 2)->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('currency', 45)->nullable();
            $table->string('channel', 45)->nullable();
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->nullOnDelete();
            $table->foreignId('donation_id')->nullable()->constrained('donations')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
