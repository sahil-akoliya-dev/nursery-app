<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plant_care_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plant_care_guide_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('plant_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('reminder_type', ['watering', 'fertilizing', 'repotting', 'pruning', 'general']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('scheduled_date');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'seasonal', 'custom', 'one_time'])->default('one_time');
            $table->integer('frequency_value')->nullable(); // For custom frequency (days)
            $table->boolean('is_completed')->default(false);
            $table->datetime('completed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'scheduled_date']);
            $table->index(['is_active', 'scheduled_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plant_care_reminders');
    }
};