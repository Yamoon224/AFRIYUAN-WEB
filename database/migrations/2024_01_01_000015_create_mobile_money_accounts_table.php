<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_money_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider', 50)->comment('orange_money, mtn_momo, moov, airtel, wave');
            $table->string('phone_number', 20);
            $table->foreignId('country_id')->constrained('countries');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'provider', 'phone_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_money_accounts');
    }
};
