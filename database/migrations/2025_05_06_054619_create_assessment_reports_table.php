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
        Schema::create('assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();

            // Client info
            $table->string('client_name');
            $table->string('client_field')->nullable();
            $table->integer('male_employees')->default(0);
            $table->integer('female_employees')->default(0);
            $table->integer('collaborators')->default(0);
            $table->integer('branches')->default(0);
            $table->string('working_hours')->nullable();

            // SWOT analysis and violations
            $table->json('strength_points')->nullable();
            $table->json('weakness_points')->nullable();
            $table->json('opportunity_points')->nullable();
            $table->json('threat_points')->nullable();
            $table->json('violations')->nullable();

            // Totals and dynamic metrics
            $table->decimal('total_violation_amount', 12, 2)->default(0);

            // Optional metadata
            $table->timestamp('generated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_reports');
    }
};
