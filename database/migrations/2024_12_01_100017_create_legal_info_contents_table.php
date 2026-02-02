<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_info_contents', function (Blueprint $table) {
            $table->id();
            $table->json('subtitle')->nullable();
            $table->json('content');
            $table->text('photo_url')->nullable();
            $table->text('video_url')->nullable();
            $table->foreignId('legal_info_title_id')->nullable()->constrained('legal_info_titles')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_info_contents');
    }
};
