<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reviewable'); // reviewable_type and reviewable_id (for products or plants) - morphs() already creates index
            $table->integer('rating'); // 1-5 stars
            $table->string('title');
            $table->text('content');
            $table->json('images')->nullable(); // Array of image paths
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(false); // Admin approval required
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            $table->timestamps();
            
            // Note: morphs() already creates index on reviewable_type and reviewable_id
            $table->index(['is_approved', 'created_at']);
            $table->index('rating');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};