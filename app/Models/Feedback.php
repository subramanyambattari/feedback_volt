<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

class Feedback extends Model
{
    protected $connection = 'mongodb';
    protected $fillable = [
        'user_id',
        'feedback_type',
        'assigned_technician_id',
        'repair_progress',
        'employee_name',
        'employee_id',
        'feedback_place',
        'feedback_date',
        'department',
        'shift_timing',
        'safety_rating',
        'workstation_rating',
        'equipment_rating',
        'overall_satisfaction',
        'strengths',
        'improvements',
        'additional_comments',
        'recommend_position',
    ];

    protected $casts = [
        'feedback_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }
}
