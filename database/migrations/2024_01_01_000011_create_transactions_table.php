<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference_number', 30)->unique()->comment('e.g. AY-20260623-000001');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('beneficiary_id')->constrained('beneficiaries');

            // Send side
            $table->decimal('send_amount', 15, 2);
            $table->char('send_currency', 3);
            $table->string('send_currency_symbol', 10);
            $table->foreignId('exchange_rate_id')->constrained('exchange_rates');
            $table->decimal('applied_rate', 20, 8)->comment('Snapshot of rate at transaction time');
            $table->decimal('fee_amount', 10, 2);
            $table->char('fee_currency', 3);
            $table->decimal('total_debit_amount', 15, 2)->comment('send_amount + fee');

            // Receive side
            $table->decimal('receive_amount', 15, 2);
            $table->char('receive_currency', 3)->default('CNY');

            // Payment source
            $table->enum('payment_method', ['card', 'mobile_money', 'bank_transfer']);
            $table->string('stripe_payment_intent_id', 255)->nullable()->index();
            $table->string('stripe_charge_id', 255)->nullable();

            // Payout to beneficiary
            $table->enum('receive_method', ['bank_transfer', 'alipay', 'wechat_pay', 'cash_pickup']);
            $table->string('payout_reference', 255)->nullable();

            // Workflow status
            $table->enum('status', [
                'initiated', 'payment_pending', 'payment_confirmed',
                'processing', 'sent_to_beneficiary',
                'completed', 'failed', 'cancelled', 'refunded'
            ])->default('initiated')->index();
            $table->text('failure_reason')->nullable();
            $table->enum('cancelled_by', ['user', 'admin', 'system'])->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Compliance
            $table->enum('compliance_status', ['clear', 'flagged', 'under_review', 'blocked'])->default('clear');
            $table->text('compliance_notes')->nullable();

            // Workflow timestamps
            $table->timestamp('initiated_at')->useCurrent();
            $table->timestamp('payment_confirmed_at')->nullable();
            $table->timestamp('processing_started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Security metadata
            $table->string('source_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_fingerprint', 255)->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
