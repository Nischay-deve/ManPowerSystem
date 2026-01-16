<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $table = 'employee_documents';
    public $timestamps = false; // uses uploaded_at

    protected $fillable = [
        'employee_id',
        'doc_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'remarks',
        'is_active',
        'uploaded_at'
    ];

    protected $casts = [
        'is_active' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
