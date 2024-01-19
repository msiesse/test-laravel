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
            return [];
        }
        $results = $job->taskResults->map(function (TaskResult $taskResult) {
            return [
                'type' => $taskResult->type,
                'result' => $taskResult->result
            ];
        });
        return $results->toArray();
    }

    public function asController(Request $request, string $jobId)
    {
        $results = $this->handle($jobId);

        return response()->json([
            'job_uuid' => $jobId,
            'results' => $results,
        ]);
    }
}
