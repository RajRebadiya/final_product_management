<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->nullable()->after('price'); // Add 'type' field
            $table->integer('level')->nullable()->after('type'); // Add 'level' field
            $table->string('barcode')->nullable()->after('level'); // Add 'barcode' field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['type', 'level', 'barcode']);
        });
    }
};
