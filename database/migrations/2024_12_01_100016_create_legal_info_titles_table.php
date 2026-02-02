<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_info_titles', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->foreignId('legal_info_subject_id')->nullable()->constrained('legal_info_subjects')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_info_titles');
    }
};
