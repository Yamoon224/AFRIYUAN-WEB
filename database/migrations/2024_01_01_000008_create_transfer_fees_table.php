<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->char('from_currency', 3)->nullable();
            $table->char('to_currency', 3)->default('CNY');
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->enum('fee_type', ['fixed', 'percentage', 'mixed']);
            $table->decimal('fixed_fee', 10, 2)->default(0);
            $table->decimal('percentage_fee', 5, 2)->default(0)->comment('e.g. 1.50 for 1.5%');
            $table->decimal('min_fee', 10, 2)->default(0);
            $table->decimal('max_fee', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_fees');
    }
};
