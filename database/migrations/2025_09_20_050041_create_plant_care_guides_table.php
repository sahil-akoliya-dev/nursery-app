<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plant_care_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('plant_type')->nullable(); // For general guides not tied to specific plants
            $table->string('title');
            $table->text('description');
            $table->enum('care_level', ['beginner', 'intermediate', 'expert'])->default('beginner');
            $table->string('light_requirements')->nullable();
            $table->string('water_needs')->nullable();
            $table->string('humidity_requirements')->nullable();
            $table->json('temperature_range')->nullable(); // min/max temperatures
            $table->string('soil_type')->nullable();
            $table->text('fertilizer_schedule')->nullable();
            $table->string('repotting_frequency')->nullable();
            $table->text('pruning_instructions')->nullable();
            $table->json('common_problems')->nullable(); // Array of common issues and solutions
            $table->json('seasonal_care')->nullable(); // Season-specific care instructions
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('plant_id');
            $table->index('is_active');
            $table->index('care_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plant_care_guides');
    }
};