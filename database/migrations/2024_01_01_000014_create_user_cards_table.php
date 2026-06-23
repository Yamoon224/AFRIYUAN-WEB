<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Stripe tokenized data (never store raw card numbers)
            $table->string('stripe_payment_method_id', 255)->unique()->comment('Stripe PaymentMethod ID (pm_xxx)');
            $table->string('stripe_customer_id', 255)->comment('Stripe Customer ID (cus_xxx)');

            // Display info (non-sensitive, provided by Stripe)
            $table->enum('card_brand', ['visa', 'mastercard', 'unionpay', 'amex', 'discover', 'jcb', 'unknown']);
            $table->string('last_four', 4)->comment('Last 4 digits of card');
            $table->tinyInteger('exp_month');
            $table->smallInteger('exp_year');
            $table->string('cardholder_name', 100)->nullable();
            $table->string('billing_country', 2)->nullable();
            $table->string('billing_zip', 20)->nullable();
            $table->string('fingerprint', 255)->nullable()->comment('Stripe fingerprint for duplicate detection');

            // Funding type
            $table->enum('funding', ['credit', 'debit', 'prepaid', 'unknown'])->default('unknown');

            // 3D Secure capability
            $table->enum('three_d_secure_usage', ['required', 'optional', 'not_supported'])->default('optional');

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
