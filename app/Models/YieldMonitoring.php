<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YieldMonitoring extends Model
{
    protected $table = 'yield_monitoring';
    protected $primaryKey = 'yield_id';
    public $timestamps = false;

    protected $fillable = [
        'farmer_id',
        'commodity_id',
        'record_date',
        'yield_amount',
        'unit'
    ];

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class, 'commodity_id', 'commodity_id');
    }
}
