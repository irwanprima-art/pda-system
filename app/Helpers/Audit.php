<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class Audit
{
    public static function log($action, $description, Request $request = null)
    {
        AuditLog::create([
            'action' => $action,
            'description' => $description,
            'user' => $request?->nik ?? 'SYSTEM',
            'ip' => $request?->ip()
        ]);
    }
}
