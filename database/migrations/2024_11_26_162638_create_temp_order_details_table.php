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
        Schema::create('temp_order_details', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->integer('temp_order_id'); // Foreign key
            $table->integer('product_id'); // Foreign key to products table
            $table->string('category_name'); // Product category
            $table->string('p_name'); // Design code
            $table->integer('qty'); // Quantity ordered
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_order_details');
    }
};
