<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum values
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'ready_for_pickup', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        } 
        // For SQLite or other databases, we need a different approach
        else {
            // Create a temporary column with the new enum values
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status_new')->default('pending');
            });

            // Copy data from the old column to the new one
            DB::table('orders')->update([
                'status_new' => DB::raw('status')
            ]);

            // Drop the old column and rename the new one
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL, revert the enum values
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        } 
        // For SQLite or other databases
        else {
            // Create a temporary column with the original enum values
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status_old')->default('pending');
            });

            // Copy data, converting 'ready_for_pickup' to 'processing'
            DB::table('orders')->update([
                'status_old' => DB::raw("CASE WHEN status = 'ready_for_pickup' THEN 'processing' ELSE status END")
            ]);

            // Drop the current column and rename the old one
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('status_old', 'status');
            });
        }
    }
};

