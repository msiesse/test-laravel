<?php

namespace App\Actions;

enum TaskType: string {
    case CALL_REASON = 'call_reason';
    case CALL_ACTIONS = 'call_actions';
    case SATISFACTION = 'satisfaction';
    case CALL_SEGMENTS = 'call_segments';
    case SUMMARY = 'summary';
}
