<?php

namespace App\Actions;

use App\Actions\TaskActions\CallActionsAction;
use App\Actions\TaskActions\CallReasonAction;
use App\Actions\TaskActions\CallSegmentsAction;
use App\Actions\TaskActions\SatisfactionAction;
use App\Actions\TaskActions\SummaryAction;
use App\Models\TaskJob;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class TaskHandler
{
    use asAction;

    public function handle(string $text, array $tasks): string
    {
        $job = TaskJob::create();
        $taskActions = array_map(function ($task) use ($text, $job) {
            return match ($task) {
                TaskType::CALL_REASON => CallReasonAction::makeJob($text, $job->id),
                TaskType::SATISFACTION => SatisfactionAction::makeJob($text, $job->id),
                TaskType::CALL_ACTIONS => CallActionsAction::makeJob($text, $job->id),
                TaskType::CALL_SEGMENTS => CallSegmentsAction::makeJob($text, $job->id),
                TaskType::SUMMARY => SummaryAction::makeJob($text, $job->id),
            };
        }, $tasks);
        Bus::batch($taskActions)->then(function () use ($job) {
            $job->update(['completed' => true]);
        })->dispatch();
        return (string)$job->id;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'max:50'],
            'tasks' => ['required', 'array', Rule::in(TaskType::cases())]
        ];
    }

    public function getValidationFailure(Validator $validator): void
    {
        $response = response()->json([
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($response);
    }

    public function asController(ActionRequest $request)
    {
        $job_uuid = $this->handle(
            $request->get('text'),
            array_map(function ($task) {
                return TaskType::from($task);
            }, $request->get('tasks'))
        );

        return response()->json([
            'job_uuid' => $job_uuid,
        ], 202);
    }
}
