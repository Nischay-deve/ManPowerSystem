<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'username',
        'email',
        'full_name',
        'password_hash',
        'is_active',
        'last_login',
        'password_reset_token',
        'password_reset_expires',
        'mfa_enabled',
        'mfa_secret',
        'created_by',
        'role',
        'last_login_at'
    ];

    protected $hidden = ['password_hash', 'mfa_secret'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function roleRel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
