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
        Schema::table('user_registrations', function (Blueprint $table) {
            $table->json('assessment_data')->nullable();
        });
        Schema::dropIfExists('free_assessments');

        Schema::create('free_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id');
            $table->json('responses')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
