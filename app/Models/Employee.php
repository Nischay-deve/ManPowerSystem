<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'sl_no',
        'employee_code',
        'first_name',
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
        'department_id'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'date_of_exit' => 'date',
        'salary' => 'decimal:2',
    ];

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(EmployeeBankAccount::class, 'employee_id');
    }

    public function primaryBankAccount()
    {
        return $this->hasOne(EmployeeBankAccount::class, 'employee_id')->where('is_primary', 1);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
