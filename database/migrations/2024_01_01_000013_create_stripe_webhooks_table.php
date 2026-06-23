<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_event_id', 255)->unique();
            $table->string('event_type', 100)->comment('e.g. payment_intent.succeeded');
            $table->json('payload');
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['event_type', 'processed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_webhooks');
    }
};
