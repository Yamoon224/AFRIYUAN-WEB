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
            $table->uuid('uuid')->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 191)->unique();
            $table->string('phone_number', 20)->unique();
            $table->string('phone_country_code', 10);
            $table->foreignId('country_id')->constrained('countries');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('profile_photo_url', 255)->nullable();
            $table->string('password', 255);
            $table->string('pin_hash', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->enum('kyc_status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->tinyInteger('kyc_level')->default(0)->comment('0=none, 1=basic, 2=full');
            $table->enum('account_status', ['active', 'suspended', 'banned'])->default('active');
            $table->char('preferred_language', 5)->default('fr');
            $table->foreignId('preferred_currency_id')->nullable()->constrained('currencies');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
