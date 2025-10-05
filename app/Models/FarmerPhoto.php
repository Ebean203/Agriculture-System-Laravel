<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerPhoto extends Model
{
    protected $table = 'farmer_photos';

    protected $fillable = [
        'farmer_id',
        'file_path',
    ];

    // The table has uploaded_at column
    const CREATED_AT = 'uploaded_at';
    const UPDATED_AT = null;

    /**
     * Get the farmer that owns the photo
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'farmer_id');
    }
}
