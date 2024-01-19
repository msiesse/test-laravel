<?php

use App\Models\TaskJob;

describe('Tests route for retrieving tasks', function () {
    test('we should receive the result of the task linked to job_id', function () {
        $taskJob = TaskJob::factory()
            ->hasTaskResults(1)
            ->create();
        $result = [
            'type' => $taskJob->taskResults->first()->type,
            'result' => $taskJob->taskResults->first()->result
        ];
        $response = $this->get('/api/tasks/' . $taskJob->id);
        $response->assertStatus(200);
        expect($response->json())->toBe([
            'job_uuid' => $taskJob->id,
            'results' => [$result],
            'completed' => true
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
            'results' => [],
            'completed' => false
        ]);
    });

    test('wehn we retrieve all the tasks the first time, we should retrieve an empty array of results the second time', function () {
        $taskJob = TaskJob::factory()
            ->hasTaskResults(1)
            ->create();
        $response = $this->get('/api/tasks/' . $taskJob->id);
        expect(count($response->json()["results"]))->toBe(1);
        $response = $this->get('/api/tasks/' . $taskJob->id);
        expect(count($response->json()["results"]))->toBe(0);
    });
});
