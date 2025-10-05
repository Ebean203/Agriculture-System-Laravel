<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaoDistributionLog extends Model
{
    protected $table = 'mao_distribution_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    
    protected $fillable = [
        'farmer_id',
        'input_id',
        'quantity_distributed',
        'date_given',
        'visitation_date',
        'distributed_by',
        'distribution_date'
    ];

    protected $casts = [
        'date_given' => 'date',
        'visitation_date' => 'date',
        'distribution_date' => 'datetime',
    ];

    /**
     * Get the farmer that received this distribution
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }

    /**
     * Get the input category for this distribution
     */
    public function input()
    {
        return $this->belongsTo(InputCategory::class, 'input_id', 'input_id');
    }
}
