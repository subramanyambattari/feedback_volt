<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->foreignId('assigned_technician_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('repair_progress')->default('queued')->after('assigned_technician_id');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_technician_id');
            $table->dropColumn('repair_progress');
        });
    }
};
