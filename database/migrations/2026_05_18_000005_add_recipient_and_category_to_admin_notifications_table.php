<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->foreignId('recipient_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('category')->default('admin')->after('recipient_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recipient_user_id');
            $table->dropColumn('category');
        });
    }
};
