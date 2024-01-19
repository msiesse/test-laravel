<?php

namespace App\Actions;

use App\Models\TaskJob;
use App\Models\TaskResult;
use Laminas\Diactoros\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class TaskRetriever
{
    use asAction;

    public function handle(string $jobId): array
    {
        $job = TaskJob::findOrFail($jobId);
        if ($job->completed === false) {
            return [
                "results" => [],
                "completed" => false
            ];
        }
        $job->taskResults->setVisible(['type', 'result']);
        $results = $job->taskResults->toArray();
        $job->taskResults()->delete();
        return [
            "results" => $results,
            "completed" => true
        ];
    }

    public function asController(Request $request, string $jobId)
    {
        $values = $this->handle($jobId);

        return response()->json([
            'job_uuid' => $jobId,
            'results' => $values['results'],
            'completed' => $values['completed']
        ]);
    }
}
