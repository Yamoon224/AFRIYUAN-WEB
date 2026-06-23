<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // All countries can be both source and destination (Africa ↔ China)
        DB::table('countries')->update(['is_source' => true, 'is_destination' => true]);

        // Add direction column to transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('direction', ['africa_to_china', 'china_to_africa'])
                  ->default('africa_to_china')
                  ->after('reference_number');
        });

        // Add beneficiary country type to beneficiaries
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->enum('beneficiary_type', ['china', 'africa'])
                  ->default('china')
                  ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('direction');
        });
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('beneficiary_type');
        });
    }
};
