<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->char('iso_code', 2)->unique();
            $table->string('phone_prefix', 10);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->boolean('is_source')->default(true);
            $table->boolean('is_destination')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('flag_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
