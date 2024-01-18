<?php

namespace App\Actions\TaskActions;

use App\Models\TaskJob;
use App\Models\TaskResult;

class SatisfactionAction extends TaskAction
{
    public function handle(string $text, string $job_id): void
    {
        TaskResult::create([
            'task_job_id' => $job_id,
            'type' => 'satisfaction',
            'result' => 'Satisfaction result'
        ]);
    }
}
