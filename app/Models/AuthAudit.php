<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthAudit extends Model
{
    protected $table = 'auth_audit';
    public $timestamps = false; // DB has created_at default

    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'created_at'
    ];
}
