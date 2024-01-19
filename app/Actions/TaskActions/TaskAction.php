<?php

namespace App\Actions\TaskActions;

use Illuminate\Bus\Batch;
use Lorisleiva\Actions\Concerns\AsAction;


abstract class TaskAction
{
    use asAction;

    abstract public function handle(string $text, string $job_id): void;
}
