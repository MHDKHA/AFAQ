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
        Schema::create('assessment_reports', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('stakeholder_name');
            $table->text('acknowledgment');
            $table->json('client_info');
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

    }
};
