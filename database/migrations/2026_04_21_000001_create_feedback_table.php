<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('employee_id')->nullable();
            $table->string('department');
            $table->string('shift_timing');
            $table->unsignedTinyInteger('safety_rating');
            $table->unsignedTinyInteger('workstation_rating');
            $table->unsignedTinyInteger('equipment_rating');
            $table->unsignedTinyInteger('overall_satisfaction');
            $table->text('strengths');
            $table->text('improvements');
            $table->text('additional_comments')->nullable();
            $table->string('recommend_position', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
