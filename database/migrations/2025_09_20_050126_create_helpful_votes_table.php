<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('helpful_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('voteable'); // voteable_type and voteable_id (for reviews) - morphs() already creates index
            $table->boolean('is_helpful'); // true for helpful, false for not helpful
            $table->timestamps();
            
            $table->unique(['user_id', 'voteable_type', 'voteable_id']);
            // Note: morphs() already creates index on voteable_type and voteable_id
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('helpful_votes');
    }
};