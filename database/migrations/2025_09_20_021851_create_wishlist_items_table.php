<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('item'); // item_id and item_type (product or plant)
            $table->timestamps();
            
            $table->unique(['user_id', 'item_id', 'item_type']);
            
            // Indexes for performance
            $table->index('user_id');
            $table->index(['item_id', 'item_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlist_items');
    }
};