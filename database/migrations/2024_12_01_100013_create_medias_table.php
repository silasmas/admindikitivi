<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('media_title')->nullable();
            $table->text('media_description')->nullable();
            $table->string('source')->nullable();
            $table->integer('belonging_count')->nullable();
            $table->time('time_length')->nullable();
            $table->text('media_url')->nullable();
            $table->text('teaser_url')->nullable();
            $table->string('author_names')->nullable();
            $table->string('artist_names')->nullable();
            $table->string('writer')->nullable();
            $table->string('director')->nullable();
            $table->date('published_date')->nullable();
            $table->text('cover_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->boolean('for_youth')->default(true);
            $table->boolean('is_live')->default(false);
            $table->unsignedBigInteger('belongs_to')->nullable();
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
