<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores individual items within an order.
     * Preserves price at time of purchase.
     * Supports polymorphic items (products or plants).
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->morphs('item'); // item_id and item_type (product or plant)
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->comment('Price at time of purchase');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('order_id');
            $table->index(['item_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

