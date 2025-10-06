<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommodityCategory extends Model
{
    protected $table = 'commodity_categories';
    protected $primaryKey = 'category_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'category_name',
    ];
}
