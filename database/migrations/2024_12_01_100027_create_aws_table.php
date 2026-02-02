<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aws', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 250)->nullable();
            $table->string('video', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aws');
    }
};
