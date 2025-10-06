<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedReport extends Model
{
    protected $table = 'generated_reports';
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    protected $fillable = [
        'report_type',
        'start_date',
        'end_date',
        'file_path',
        'staff_id',
        'timestamp'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'timestamp' => 'datetime'
    ];

    /**
     * Get the staff member who generated this report
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(MaoStaff::class, 'staff_id', 'staff_id');
    }

    /**
     * Get formatted report type name
     */
    public function getFormattedTypeAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->report_type));
    }

    /**
     * Get the full file URL
     */
    public function getFileUrlAttribute(): string
    {
        return asset($this->file_path);
    }
}
