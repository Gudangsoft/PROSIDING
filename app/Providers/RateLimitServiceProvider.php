<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Login rate limit - 5 attempts per minute
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.'
                ], 429);
            });
        });

        // Register rate limit - 3 attempts per hour
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        // Password reset rate limit - 3 attempts per hour
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perHour(3)->by($request->email ?? $request->ip());
        });

        // API rate limit - 60 requests per minute
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // File upload rate limit - 20 uploads per hour
        RateLimiter::for('file-upload', function (Request $request) {
            return Limit::perHour(20)->by($request->user()?->id ?: $request->ip());
        });

        // Email sending rate limit - 10 emails per hour
        RateLimiter::for('email', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id ?: $request->ip());
        });

        // Paper submission rate limit - 5 submissions per day
        RateLimiter::for('paper-submission', function (Request $request) {
            return Limit::perDay(5)->by($request->user()?->id);
        });

        // Review submission rate limit - 10 reviews per day
        RateLimiter::for('review-submission', function (Request $request) {
            return Limit::perDay(10)->by($request->user()?->id);
        });

        // General form submission - 20 per minute
        RateLimiter::for('form-submission', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });
    }
}
