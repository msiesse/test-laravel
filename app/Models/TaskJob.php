<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskJob extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'task_jobs';

    protected $fillable = [
        'id',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public function taskResults(): HasMany
    {
        return $this->hasMany(TaskResult::class);
    }
}
