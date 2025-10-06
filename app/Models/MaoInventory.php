<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaoInventory extends Model
{
    protected $table = 'mao_inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'input_id',
        'quantity_on_hand',
        'last_updated'
    ];
}
