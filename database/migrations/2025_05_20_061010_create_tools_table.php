<?php
// database/migrations/2025_05_25_create_tools_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('role_name')->nullable(); // The role name users need to access this tool
            $table->timestamps();
        });

        // Add tool_id to domains table
        Schema::table('domains', function (Blueprint $table) {
            $table->foreignId('tool_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropForeign(['tool_id']);
            $table->dropColumn('tool_id');
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('tools');
    }
};
