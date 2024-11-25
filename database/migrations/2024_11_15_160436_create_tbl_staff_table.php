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
        Schema::create('tbl_staff', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Staff name
            $table->string('mobile_no')->unique(); // Mobile number, must be unique
            $table->string('emp_code')->unique(); // Employee code, must be unique
            $table->integer('status'); // Status with default value
            $table->unsignedBigInteger('permission_id'); // Foreign key to permissions table
            $table->string('password'); // Password for authentication
            $table->rememberToken(); // Remember token for "Remember Me" functionality
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_staff');
    }
};
