<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('medias')->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('sessions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_session');
    }
};
