<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyWorkUpdate extends Model
{
   protected $fillable = [
        'user_id',
        'task_id',
        'work_date',
        'work_done',
        'pending_reason',
        'progress_percentage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'progress_percentage' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
