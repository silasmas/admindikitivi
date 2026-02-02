<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->json('status_name');
            $table->text('status_description')->nullable();
            $table->string('icon', 45)->nullable();
            $table->string('color', 45)->nullable();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
