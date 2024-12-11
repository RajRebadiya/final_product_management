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
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->string('packing_bag')->after('order_date')->nullable(); // Add 'packing_bad' column
            $table->unsignedBigInteger('created_by')->after('packing_bag')->nullable(); // Add 'created_by' column

            // Add foreign key constraint
            $table->foreign('created_by')->references('id')->on('tbl_staff')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->dropForeign(['created_by']); // Drop the foreign key
            $table->dropColumn(['packing_bag', 'created_by']); // Drop the columns
        });
    }
};
