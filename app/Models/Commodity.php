<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $table = 'commodities';
    protected $primaryKey = 'commodity_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'commodity_name'
    ];
}
