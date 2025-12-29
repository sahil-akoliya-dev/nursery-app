<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('google_id')->nullable();
            $table->string('role')->default('customer')->comment('super_admin, admin, manager, customer');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('email');
            $table->index('role');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};