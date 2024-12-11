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
        Schema::table('tbl_staff', function (Blueprint $table) {
            // Add the role_id field and set it as a foreign key
            $table->unsignedBigInteger('role_id')->nullable();  // Nullable in case you want to handle staff without roles initially

            // Foreign key constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_staff', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
