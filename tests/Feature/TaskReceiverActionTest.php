<?php

use App\Actions\TaskActions\CallActionsAction;
use App\Actions\TaskActions\CallReasonAction;
use App\Actions\TaskActions\CallSegmentsAction;
use App\Actions\TaskActions\SatisfactionAction;
use App\Actions\TaskActions\SummaryAction;
use App\Actions\TaskReceiver;
use App\Actions\TaskType;
use App\Models\TaskJob;
use App\Models\TaskResult;
use Illuminate\Support\Facades\Queue;

describe('Task receiver action', function () {
    test('it should create a task job when request validated', function () {
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON]);
        expect(TaskJob::count())->toBe(1);
    });

    test('it should dispatch when only one task is requested', function (TaskType $taskType, $action) {
        Queue::fake();
        TaskReceiver::run('Hello world!', [$taskType]);
        $action::assertPushed();
    })->with([
        [TaskType::CALL_REASON, CallReasonAction::class],
        [TaskType::SATISFACTION, SatisfactionAction::class],
        [TaskType::CALL_ACTIONS, CallActionsAction::class],
        [TaskType::CALL_SEGMENTS, CallSegmentsAction::class],
        [TaskType::SUMMARY, SummaryAction::class]
    ]);

    test('it should handle multiple tasks', function () {
        Queue::fake();
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION]);
        CallReasonAction::assertPushed();
        SatisfactionAction::assertPushed();
    });

    test('it should put the result of a task in queue', function () {
        config(['queue.default' => 'sync']);
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON]);
        expect(TaskResult::count())->toBe(1);
    });

    test('it should put the result of all tasks in queue', function () {
        config(['queue.default' => 'sync']);
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION, TaskType::CALL_ACTIONS, TaskType::CALL_SEGMENTS, TaskType::SUMMARY]);
        expect(TaskResult::count())->toBe(5);
    });

    test('the task job should be marked as completed when all tasks are done', function () {
        config(['queue.default' => 'sync']);
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION, TaskType::CALL_ACTIONS, TaskType::CALL_SEGMENTS, TaskType::SUMMARY]);
        expect(TaskJob::first()->completed)->toBe(true);
    });

    test('the task job should not be marked as completed when not all tasks are done', function () {
        TaskReceiver::run('Hello world!', [TaskType::CALL_REASON, TaskType::SATISFACTION, TaskType::CALL_ACTIONS, TaskType::CALL_SEGMENTS]);
        expect(TaskJob::first()->completed)->toBe(false);
    });


});
