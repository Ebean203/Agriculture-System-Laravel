<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = ['role'];

    public function staff()
    {
        return $this->hasMany(MaoStaff::class, 'role_id', 'role_id');
    }
}
