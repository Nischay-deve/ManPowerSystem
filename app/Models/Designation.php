<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'title',
        'is_active',
        'notes',
    ];

    // ✅ CORRECT
    public function employees()
    {
        return $this->hasMany(
            \App\Models\Employee::class,
            'designation_id',
            'id'
        );
    }
}
