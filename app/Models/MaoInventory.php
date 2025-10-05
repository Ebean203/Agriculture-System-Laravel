<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaoInventory extends Model
{
    protected $table = 'mao_inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'item_name',
        'item_type',
        'quantity_on_hand',
        'unit',
        'last_updated'
    ];
}
