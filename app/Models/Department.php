<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'integer',
    ];

    public function designations()
    {
        return $this->hasMany(Designation::class, 'department_id');
    }
}
