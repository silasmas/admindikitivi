<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->nullable()->constrained('books')->nullOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('medias')->nullOnDelete();
            $table->foreignId('pricing_id')->nullable()->constrained('pricings')->nullOnDelete();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
