<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add new column
        Schema::table('designations', function (Blueprint $table) {
            $table->tinyInteger('is_active')->default(1)->after('status');
        });

        // 2) Copy existing enum values into 1/0
        DB::statement("UPDATE designations SET is_active = CASE WHEN status='Active' THEN 1 ELSE 0 END");

        // 3) Drop old enum column
        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        // rollback: add enum back
        Schema::table('designations', function (Blueprint $table) {
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
        });

        // restore enum based on is_active
        DB::statement("UPDATE designations SET status = CASE WHEN is_active=1 THEN 'Active' ELSE 'Inactive' END");

        // drop is_active
        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
