<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('auditable_type', 100)->comment('e.g. App\\Models\\Transaction');
            $table->unsignedBigInteger('auditable_id');
            $table->string('event', 50)->comment('created, updated, deleted, login, etc.');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->enum('actor_type', ['user', 'admin', 'system']);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['actor_type', 'actor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
