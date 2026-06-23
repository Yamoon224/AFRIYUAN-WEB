<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->char('from_currency', 3);
            $table->char('to_currency', 3);
            $table->decimal('rate', 20, 8)->comment('Market rate');
            $table->decimal('margin_rate', 20, 8)->comment('Rate applied to customer (with margin)');
            $table->decimal('margin_percent', 5, 2)->comment('Margin percentage e.g. 2.50');
            $table->string('source', 50)->default('fixer.io');
            $table->timestamp('fetched_at');
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->nullable();

            $table->index(['from_currency', 'to_currency', 'fetched_at']);

            $table->foreign('from_currency')->references('code')->on('currencies');
            $table->foreign('to_currency')->references('code')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
