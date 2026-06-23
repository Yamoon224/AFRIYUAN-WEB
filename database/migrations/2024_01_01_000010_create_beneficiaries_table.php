<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nickname', 100);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->enum('receive_method', ['bank_transfer', 'alipay', 'wechat_pay', 'cash_pickup']);

            // Bank transfer fields
            $table->string('bank_name', 150)->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->string('bank_swift_code', 20)->nullable();
            $table->string('bank_branch', 150)->nullable();

            // Digital wallet fields
            $table->string('digital_wallet_id', 100)->nullable()->comment('Alipay or WeChat account ID');
            $table->enum('digital_wallet_type', ['alipay', 'wechat_pay'])->nullable();

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
