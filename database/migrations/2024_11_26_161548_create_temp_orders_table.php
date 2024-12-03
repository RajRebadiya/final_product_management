<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_orders', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->integer('party_id');
            $table->string('order_number')->unique(); // Custom order number
            $table->date('order_date'); // Date of the order
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_orders');
    }
};
