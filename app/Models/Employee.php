<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_code',
        'first_name',
        'surname',
        'gender',
        'father_or_spouse_name',
        'date_of_birth',
        'nationality',
        'education_level',
        'photo',
        'date_of_joining',
        'designation_id',
        'department_id',
        'category',
        'address_type',
        'employment_type',
        'mobile',
        'uan',
        'pan',
        'esic_ip',
        'lwf',
        'aadhaar',
        'present_address',
        'permanent_address',
        'service_book_no',
        'date_of_exit',
        'reason_for_exit',
        'mark_of_identification',
        'remarks',
        'salary',
        'row_version',
        'created_by',
        'updated_by',
    ];
}
