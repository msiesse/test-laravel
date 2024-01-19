<?php

use App\Actions\TaskActions\CallActionsAction;
use App\Actions\TaskActions\CallReasonAction;
use App\Actions\TaskActions\CallSegmentsAction;
use App\Actions\TaskActions\SatisfactionAction;
use App\Actions\TaskActions\SummaryAction;
use App\Actions\TaskHandler;
use App\Actions\TaskType;
use App\Models\TaskJob;
use App\Models\TaskResult;
use Illuminate\Support\Facades\Queue;

describe('Task handler action', function () {
    test('it should create a task job when request validated', function () {
        TaskHandler::run('Hello world!', [TaskType::CALL_REASON]);
        expect(TaskJob::count())->toBe(1);
    });

    test('it should dispatch when only one task is requested', function (TaskType $taskType, $action) {
        Queue::fake();
        TaskHandler::run('Hello world!', [$taskType]);
        $action::assertPushed(1);
    })->with([
        [TaskType::CALL_REASON, CallReasonAction::class],
        [TaskType::SATISFACTION, SatisfactionAction::class],
        [TaskType::CALL_ACTIONS, CallActionsAction::class],
        [TaskType::CALL_SEGMENTS, CallSegmentsAction::class],
        [TaskType::SUMMARY, SummaryAction::class]
    ]);

    test('it should handle multiple tasks', function () {
        Queue::fake();
        TaskHandler::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION]);
        CallReasonAction::assertPushed(1);
        SatisfactionAction::assertPushed(1);
    });

    test('it should save the result of a task', function () {
        TaskHandler::run('Hello world!', [TaskType::CALL_REASON]);
        expect(TaskResult::count())->toBe(1);
    });

    test('it should save the result of all tasks', function () {
        TaskHandler::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION, TaskType::CALL_ACTIONS, TaskType::CALL_SEGMENTS, TaskType::SUMMARY]);
        expect(TaskResult::count())->toBe(5);
    });

    test('the task job should be marked as completed when all tasks are done', function () {
        TaskHandler::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION, TaskType::CALL_ACTIONS, TaskType::CALL_SEGMENTS, TaskType::SUMMARY]);
        expect(TaskJob::first()->completed)->toBe(true);
    });
});
