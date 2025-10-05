<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MaoStaff extends Authenticatable
{
    use Notifiable;

    protected $table = 'mao_staff';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'contact_number',
        'username',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship with roles
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // Override getAuthPassword to use 'password' field
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Override getAuthIdentifierName to use 'staff_id'
    public function getAuthIdentifierName()
    {
        return 'staff_id';
    }
}
