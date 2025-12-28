<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change 'role' column from VARCHAR to ENUM (MySQL only - SQLite doesn't support ENUM)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'vendor', 'admin', 'manager', 'super_admin') NOT NULL DEFAULT 'customer'");
        }
        // SQLite already uses TEXT type which is flexible enough
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'role' column back to VARCHAR (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'customer' COMMENT 'super_admin, admin, manager, customer'");
        }
    }
};
