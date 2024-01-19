<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_results', function (Blueprint $table) {
            $table->uuid('id');
            $table->timestamps();
            $table->foreignUuid('task_job_id')->constrained()->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type');
            $table->string('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_results');
    }
};
