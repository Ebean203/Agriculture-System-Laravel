<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputCategory extends Model
{
    protected $table = 'input_categories';
    protected $primaryKey = 'input_id';
    public $timestamps = false;
    
    protected $fillable = [
        'input_name',
        'unit',
        'requires_visitation'
    ];

    protected $casts = [
        'requires_visitation' => 'boolean',
    ];

    /**
     * Get the inventory for this input category
     */
    public function inventory()
    {
        return $this->hasOne(MaoInventory::class, 'input_id', 'input_id');
    }

    /**
     * Get the distribution logs for this input category
     */
    public function distributionLogs()
    {
        return $this->hasMany(MaoDistributionLog::class, 'input_id', 'input_id');
    }
}
