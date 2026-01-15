<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Employee Code entered manually
            $table->string('employee_code', 100)->unique();

            // Personal
            $table->string('name', 200);
            $table->string('surname', 200)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('father_or_spouse_name', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('education_level', 100)->nullable();

            // Joining / Job
            $table->date('date_of_joining')->nullable();
            $table->string('designation', 150)->nullable();
            $table->string('category', 100)->nullable();
            $table->enum('address_type', ['HS', 'S', 'SS', 'US'])->nullable();

            $table->enum('employment_type', [
                'Regular',
                'Contract',
                'Apprentice',
                'Temporary'
            ])->default('Regular');

            // Contact / Statutory
            $table->string('mobile', 30)->nullable();
            $table->string('uan', 50)->nullable();
            $table->string('pan', 20)->nullable();
            $table->string('esic_ip', 100)->nullable();
            $table->string('lwf', 100)->nullable();
            $table->string('aadhaar', 20)->nullable();

            // Bank Details
            $table->string('bank_account_no', 50)->nullable();
            $table->string('bank_name', 150)->nullable();
            $table->string('bank_ifsc', 30)->nullable();

            // Address
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();

            // Service / Exit
            $table->string('service_book_no', 100)->nullable();
            $table->date('date_of_exit')->nullable();
            $table->string('reason_for_exit', 255)->nullable();

            // Other
            $table->string('mark_of_identification', 255)->nullable();

            // Uploads
            $table->string('photo')->nullable();
            $table->string('specimen_signature')->nullable();

            $table->text('remarks')->nullable();

            // Salary
            $table->decimal('salary', 12, 2)->default(0.00);

            // Laravel defaults
            $table->timestamps();
            $table->softDeletes();

            // Helpful indexes
            $table->index('employee_code');
            $table->index('mobile');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
