<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Only log if the user is authenticated
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $this->getAction($request),
                'module' => $this->getModule($request),
                'details' => json_encode($request->all()),
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }

    private function getAction($request)
    {
        if ($request->isMethod('post')) return 'Created';
        if ($request->isMethod('put') || $request->isMethod('patch')) return 'Updated';
        if ($request->isMethod('delete')) return 'Deleted';
        return 'Viewed';
    }

    private function getModule($request)
    {
        return ucfirst($request->segment(1)); // Use the first URL segment as the module name
    }
}
