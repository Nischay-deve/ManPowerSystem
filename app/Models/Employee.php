<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_code',
        'name',
        'surname',
        'gender',
        'father_or_spouse_name',
        'date_of_birth',
        'nationality',
        'education_level',
        'date_of_joining',
        'designation_id',
        'category',
        'address_type',
        'employment_type',
        'mobile',
        'uan',
        'pan',
        'esic_ip',
        'lwf',
        'aadhaar',
        'bank_account_no',
        'bank_name',
        'bank_ifsc',
        'present_address',
        'permanent_address',
        'service_book_no',
        'date_of_exit',
        'reason_for_exit',
        'mark_of_identification',
        'photo',
        'specimen_signature',
        'remarks',
        'salary',
    ];

    public function designation()
    {
        return $this->belongsTo(
            \App\Models\Designation::class,
            'designation_id',
            'id'
        );
    }
}
