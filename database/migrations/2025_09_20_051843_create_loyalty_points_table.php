<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('points')->default(0);
            $table->string('type'); // 'earned', 'redeemed', 'expired', 'bonus'
            $table->string('source'); // 'purchase', 'review', 'referral', 'signup', 'redeem'
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('review_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('points_balance')->default(0); // Running balance
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index('expires_at');
            $table->index('user_id');
            $table->index('created_at');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};