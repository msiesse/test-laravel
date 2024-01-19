<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResult extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = "task_results";

    protected $fillable = [
        'task_job_id',
        'type',
        'result'
    ];
}
