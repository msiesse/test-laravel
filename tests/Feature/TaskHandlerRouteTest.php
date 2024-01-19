<?php

use App\Models\TaskJob;
use Illuminate\Support\Str;

describe('Tests for receiving tasks', function () {
    test('it should send back an job uuid', function () {
        $response = $this->post('/api/tasks', [
            'text' => 'Hello world!',
            'tasks' => [
                'call_reason',
            ],
        ]);
        $response->assertStatus(202);
        $response->assertJson([
            'job_uuid' => TaskJob::first()->id,
        ]);
    });

    test('it should send back an error if no text is provided', function () {
        $response = $this->post('/api/tasks', [
            'tasks' => [
                'call_reason',
            ],
        ]);
        $response->assertStatus(422);
    });

    test('it should be valid if the text is 50 characters', function () {
        $response = $this->post('/api/tasks', [
            'text' => Str::random(50),
            'tasks' => [
                'call_reason',
            ],
        ]);
        $response->assertStatus(202);
    });

    test('it should send an error if the text is more than 50 characters', function () {
        $response = $this->post('/api/tasks', [
            'text' => Str::random(51),
            'tasks' => [
                'call_reason',
            ],
        ]);
        $response->assertStatus(422);
    });

    test('it should send an error if no tasks are provided', function () {
        $response = $this->post('/api/tasks', [
            'text' => 'Hello world!',
            'tasks' => [],
        ]);
        $response->assertStatus(422);
    });

    test('it should send an error if one of the task is not valid', function () {
        $response = $this->post('/api/tasks', [
            'text' => 'Hello world!',
            'tasks' => [
                'call_reason',
                'invalid_task'
            ],
        ]);
        $response->assertStatus(422);
    });
});
