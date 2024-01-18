<?php

namespace App\Actions\TaskActions;

use App\Models\TaskJob;
use App\Models\TaskResult;

class SummaryAction extends TaskAction
{
    public function handle(string $text, string $job_id): void
    {
        TaskResult::create([
            'task_job_id' => $job_id,
            'type' => 'summary',
            'result' => 'Summary result'
        ]);
    }
}
