<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log only important actions
        if ($this->shouldLog($request)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * Determine if the request should be logged
     */
    private function shouldLog(Request $request): bool
    {
        // Log POST, PUT, PATCH, DELETE requests
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return true;
        }

        // Log specific GET routes (login, logout, etc)
        $logRoutes = ['login', 'logout', 'register'];
        foreach ($logRoutes as $route) {
            if (str_contains($request->path(), $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the activity
     */
    private function logActivity(Request $request, Response $response): void
    {
        $user = $request->user();
        
        $logData = [
            'user_id' => $user?->id ?? 'guest',
            'user_email' => $user?->email ?? 'guest',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status_code' => $response->getStatusCode(),
            'timestamp' => now()->toDateTimeString(),
        ];

        // Don't log sensitive data
        $requestData = $request->except(['password', 'password_confirmation', 'token', '_token']);
        
        if (!empty($requestData)) {
            $logData['request_data'] = $requestData;
        }

        Log::channel('single')->info('User Activity', $logData);
    }
}
