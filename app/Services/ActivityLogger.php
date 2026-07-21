<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $module,
        string $action,
        $transactionId = null,
        array $payload = []
    ) {
        ActivityLog::create([
            'module' => $module,
            'action' => $action,
            'transaction_id' => $transactionId,
            'user_id' => Auth::id(),
            'payload' => $payload ?: null,
        ]);
    }
}
