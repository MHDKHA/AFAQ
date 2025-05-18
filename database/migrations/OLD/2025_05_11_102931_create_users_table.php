<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->enum('role', ['free', 'paid'])->default('free');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            // Foreign key constraint to ensure id matches user id
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};

