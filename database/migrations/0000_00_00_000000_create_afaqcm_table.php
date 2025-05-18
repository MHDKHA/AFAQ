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
        Schema::dropIfExists('assessment_reports');
        Schema::dropIfExists('assessment_items');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('companies');

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->string('stakeholder_name');
            $table->string('stakeholder_name_ar');
            $table->json('client_info');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // User details table - links users to companies
        Schema::create('user_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->enum('role', ['free', 'paid'])->default('free');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Domains table
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('name_ar');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Criteria table
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->text('question_ar');
            $table->integer('order')->default(0);
            $table->boolean('is_premium')->default(false); // To identify premium criteria
            $table->timestamps();
        });

        // Assessments table - links users to companies
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->date('date');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Assessment items table
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            // instead of ->constrained() with no args...
            $table->foreignId('criteria_id')
                ->constrained('criteria')    // ← explicitly name the table
                ->onDelete('cascade');
            $table->boolean('is_available')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Assessment reports table
        Schema::create('assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->string('company_id');
            $table->text('acknowledgment');
            $table->json('attachments')->nullable();
            $table->json('work_environment');
            $table->json('panel_notes')->nullable();
            $table->json('assessment_results');
            $table->json('expected_violations')->nullable();
            $table->decimal('total_fine', 12, 2)->default(0);
            $table->json('follow_up_services')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key constraints errors


    }
};
