<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('surname')->nullable();
            $table->string('name')->default('DIKITIVI');
            $table->string('gender', 45)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('p_o_box', 45)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 45)->nullable()->unique();
            $table->string('username', 45)->nullable()->unique();
            $table->text('password')->nullable();
            $table->unsignedBigInteger('belongs_to')->nullable();
            $table->string('parental_code', 45)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();
            $table->string('api_token', 100)->nullable();
            $table->text('avatar_url')->nullable();
            $table->string('id_card_type')->nullable();
            $table->text('id_card_recto')->nullable();
            $table->text('id_card_verso')->nullable();
            $table->string('prefered_theme', 45)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
