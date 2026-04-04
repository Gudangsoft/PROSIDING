<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use App\Models\Paper;
use App\Models\Payment;
use App\Models\AbstractSubmission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share sidebar notification counts for admin menus
        View::composer('layouts.app', function ($view) {
            if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isEditor())) {
                // Papers pending review (submitted, screening, in_review)
                $pendingPapers = Paper::whereIn('status', ['submitted', 'screening', 'in_review', 'revised'])->count();
                
                // Payments pending verification (uploaded but not verified)
                $pendingPayments = Payment::where('status', 'uploaded')->count();
                
                // Abstracts pending review
                $pendingAbstracts = AbstractSubmission::where('status', 'pending')->count();
                
                // Papers with revision submitted (need re-review)
                $revisedPapers = Paper::where('status', 'revised')->count();
                
                // Camera-ready submissions pending review
                $pendingCameraReady = Paper::whereNotNull('camera_ready_path')
                    ->where('camera_ready_status', 'submitted')
                    ->count();
                
                // LOA/Acceptance Letters pending
                $pendingLoa = Paper::where('status', 'accepted')
                    ->whereNull('acceptance_letter_path')
                    ->count();
                
                $view->with([
                    'sidebarPendingPapers' => $pendingPapers,
                    'sidebarPendingPayments' => $pendingPayments,
                    'sidebarPendingAbstracts' => $pendingAbstracts,
                    'sidebarRevisedPapers' => $revisedPapers,
                    'sidebarPendingCameraReady' => $pendingCameraReady,
                    'sidebarPendingLoa' => $pendingLoa,
                ]);
            }
        });
    }
}
