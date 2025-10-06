<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseholdInfo extends Model
{
    protected $table = 'household_info';
    public $timestamps = false;

    protected $fillable = [
        'farmer_id',
        'civil_status',
        'spouse_name',
        'household_size',
        'education_level',
        'occupation',
    ];

    /**
     * Get the farmer that owns the household info
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}
