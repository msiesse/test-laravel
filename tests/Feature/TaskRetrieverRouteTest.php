<?php

use App\Models\TaskJob;

describe('Tests route for retrieving tasks', function () {
    test('we should receive the result of the task linked to job_id', function () {
        $taskJob = TaskJob::factory()
            ->hasTaskResults(1)
            ->create();
        $response = $this->get('/api/tasks/' . $taskJob->id);
        $response->assertStatus(200);
        expect($response->json())->toBe([
            'job_uuid' => $taskJob->id,
            'results' => [
                [
                    'type' => $taskJob->taskResults->first()->type,
                    'result' => $taskJob->taskResults->first()->result
                ]
            ]
        ]);
    });

    test('we should receive an error if the job_id does not exist', function () {
        $response = $this->get('/api/tasks/123');
        $response->assertStatus(404);
    });

    test('we should receive nothing if the job task is not completed', function () {
        $taskJob = TaskJob::factory()
            ->hasTaskResults(1)
            ->create([
                'completed' => false
            ]);
        $response = $this->get('/api/tasks/' . $taskJob->id);
        $response->assertStatus(200);
        expect($response->json())->toBe([
            'job_uuid' => $taskJob->id,
            'results' => []
        ]);
    });
});
