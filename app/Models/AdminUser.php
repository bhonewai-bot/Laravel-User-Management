<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $table = 'admin_users';

    public $timestamps = true;

    protected $fillable = [
        'name', 'username', 'role_id', 'phone', 'email', 'address', 'password', 'gender', 'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'gender' => 'boolean',
        'is_active' => 'boolean',
    ];
}