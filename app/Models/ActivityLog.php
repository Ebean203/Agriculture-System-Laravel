<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'staff_id',
        'action_type',
        'action',
        'timestamp'
    ];

    public function staff()
    {
        return $this->belongsTo(MaoStaff::class, 'staff_id', 'staff_id');
    }
}
