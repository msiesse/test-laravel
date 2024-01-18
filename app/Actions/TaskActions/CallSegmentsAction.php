<?php

namespace App\Actions\TaskActions;

use App\Models\TaskResult;

class CallSegmentsAction extends TaskAction
{
    public function handle(string $text, string $job_id): void
    {
        TaskResult::create([
            'task_job_id' => $job_id,
            'type' => 'call_segments',
            'result' => 'Call Segments result'
        ]);
    }
}
