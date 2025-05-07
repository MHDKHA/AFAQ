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
        Schema::dropIfExists('assessment_items');

    }

    public function down(): void
    {
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_available')->default(false); // نعم/لا
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();


        });
    }
};
