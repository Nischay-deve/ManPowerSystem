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

            // Basic identifiers
            $table->integer('sl_no')->nullable();
            $table->string('employee_code', 100)->unique();

            // Personal details
            $table->string('first_name', 200);
            $table->string('surname', 200)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('father_or_spouse_name', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('education_level', 100)->nullable();
            $table->string('photo')->nullable();

            // Employment details
            $table->date('date_of_joining')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('address_type', 20)->nullable();

            $table->enum('employment_type', [
                'Regular',
                'Contract',
                'Apprentice',
                'Temporary'
            ])->default('Regular');

            // Contact & statutory
            $table->string('mobile', 30)->nullable();
            $table->string('uan', 50)->nullable();
            $table->string('pan', 20)->nullable();
            $table->string('esic_ip', 100)->nullable();
            $table->string('lwf', 100)->nullable();
            $table->string('aadhaar', 20)->nullable();

            // Address
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();

            // Service & exit
            $table->string('service_book_no', 100)->nullable();
            $table->date('date_of_exit')->nullable();
            $table->string('reason_for_exit', 255)->nullable();

            // Other info
            $table->string('mark_of_identification', 255)->nullable();
            $table->text('remarks')->nullable();

            // Salary & versioning
            $table->decimal('salary', 12, 2)->default(0.00);
            $table->integer('row_version')->default(1);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Laravel defaults
            $table->timestamps();
            $table->softDeletes();

            // Indexes & foreign keys
       

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
