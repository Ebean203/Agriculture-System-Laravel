<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $table = 'farmers';
    protected $primaryKey = 'farmer_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'farmer_id',
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'birth_date',
        'gender',
        'contact_number',
        'barangay_id',
        'address_details',
        'is_member_of_4ps',
        'is_ip',
        'is_rsbsa',
        'is_ncfrs',
        'is_boat',
        'is_fisherfolk',
        'other_income_source',
        'land_area_hectares',
        'archived',
        'archive_reason',
        'registration_date'
    ];

    protected $casts = [
        'is_member_of_4ps' => 'boolean',
        'is_ip' => 'boolean',
        'is_rsbsa' => 'boolean',
        'is_ncfrs' => 'boolean',
        'is_fisherfolk' => 'boolean',
        'is_boat' => 'boolean',
        'archived' => 'boolean',
        'birth_date' => 'date',
        'registration_date' => 'datetime',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function yieldMonitoring()
    {
        return $this->hasMany(YieldMonitoring::class, 'farmer_id', 'farmer_id');
    }

    public function householdInfo()
    {
        return $this->hasOne(HouseholdInfo::class, 'farmer_id', 'farmer_id');
    }

    public function commodities()
    {
        return $this->belongsToMany(Commodity::class, 'farmer_commodities', 'farmer_id', 'commodity_id')
                    ->withPivot('is_primary', 'years_farming')
                    ->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(FarmerPhoto::class, 'farmer_id', 'farmer_id');
    }
}
