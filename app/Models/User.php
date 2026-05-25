<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection = 'mongodb';

    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin(): bool
    {
        return isset($this->role) && ($this->role === 'admin' || $this->role === 'administrator');
    }

    public function isTechnician(): bool
    {
        return isset($this->role) && $this->role === 'technician';
    }
}