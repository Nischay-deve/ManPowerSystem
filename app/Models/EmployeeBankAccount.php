<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmployeeBankAccount extends Model
{
    protected $table = 'employee_bank_accounts';

    protected $fillable = [
        'employee_id',
        'account_number',
        'account_last4',
        'account_holder_name',
        'bank_name',
        'branch',
        'ifsc',
        'is_primary',
        'verification_status',
        'verification_notes',
        'verified_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_primary' => 'integer',
        'verified_at' => 'datetime',
    ];

    // Save: encrypt into blob + store last4
    public function setAccountNumberAttribute($value)
    {
        if ($value === null || $value === '') return;

        $this->attributes['account_number'] = Crypt::encryptString($value);
        $this->attributes['account_last4']  = substr($value, -4);
    }

    // Read: try decrypt; if not encrypted (old hex blob => ASCII digits), return raw
    public function getAccountNumberAttribute($value)
    {
        if (!$value) return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            // old data might be plain ASCII digits stored in blob
            return $value;
        }
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
