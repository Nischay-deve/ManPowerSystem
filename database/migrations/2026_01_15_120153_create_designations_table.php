<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();

            $table->string('title', 150)->unique();             // Designation Title
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
