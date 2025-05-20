<?php
// database/migrations/2025_05_25_create_temporary_assessments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->json('responses')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::table('assessments', function (Blueprint $table) {
            $table->foreignId('tool_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_assessments');
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['tool_id']);
            $table->dropColumn('tool_id');
        });
    }
};
