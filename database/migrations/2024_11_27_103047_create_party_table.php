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
        Schema::create('party', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('name'); // Party name
            $table->string('email')->unique(); // Party email (unique)
            $table->string('mobile')->unique(); // Mobile number (unique)
            $table->string('address'); // Party address
            $table->string('city'); // City
            $table->string('gst_no')->nullable(); // GST number (nullable)
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party');
    }
};
