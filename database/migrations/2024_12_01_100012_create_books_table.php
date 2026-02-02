<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_title')->nullable();
            $table->text('book_url')->nullable();
            $table->string('author_names')->nullable();
            $table->string('editor')->nullable();
            $table->text('cover_url')->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->boolean('for_youth')->default(true);
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
