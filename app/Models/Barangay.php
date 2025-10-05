<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $table = 'barangays';
    protected $primaryKey = 'barangay_id';
    public $timestamps = false;

    protected $fillable = [
        'barangay_name'
    ];

    public function farmers()
    {
        return $this->hasMany(Farmer::class, 'barangay_id', 'barangay_id');
    }
}
