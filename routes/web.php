<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentResultsController;
use App\Http\Controllers\AssesmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssessmentDashboardController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home page
Route::get('/', function () {
    return Inertia::render('Home', [
        'tools' => \App\Models\Tool::where('is_active', true)->get(),
        'locale' => app()->getLocale()
    ]);
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Registration
Route::middleware('web')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('registration.form');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
});

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/start', [ToolController::class, 'startAssessment'])
    ->middleware('auth')
    ->name('assessment.start');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assessments
    Route::get('/assessment/{assessment}/fill', [AssessmentController::class, 'fill'])
        ->name('assessment.fill');
    Route::post('/assessment/{assessment}/save', [AssessmentController::class, 'save'])
        ->name('assessment.save');
    Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
        ->name('assessment.print');

    // Dashboard sections
    Route::get('/dashboard/assessments', function () {
        $assessments = auth()->user()->assesments()->with(['tool', 'items'])->latest()->get();
        $assessments->each(function ($assessment) {
            $assessment->available_count = $assessment->items->where('is_available', true)->count();
            $assessment->items_count = $assessment->items->count();
        });
        return Inertia::render('Dashboard/Assessments', [
            'assessments' => $assessments,
            'tools' => \App\Models\Tool::where('is_active', true)->get(),
            'locale' => app()->getLocale()
        ]);
    })->name('dashboard.assessments');

    // Assessment Reports
    Route::get('/assessment-reports/{record}/export-pdf', [ReportController::class, 'exportPdf'])
        ->name('assessment-reports.export-pdf');
    Route::get('/assessment-dashboard/{assessment}', [AssessmentDashboardController::class, 'show'])
        ->name('assessment-dashboard.show');
    Route::post('/assessment-dashboard/{assessment}/send-report', [AssessmentDashboardController::class, 'sendReport'])
        ->name('assessment-dashboard.send-report');

    // Subscription
    Route::get('/subscribe', [SubscriptionController::class, 'showOptions'])
        ->name('subscribe');
    Route::post('/subscribe/process', [SubscriptionController::class, 'process'])
        ->name('subscribe.process');
});

// Public assessment save route (no auth required)
Route::post('/assessments/{id}/save', [AssessmentDashboardController::class, 'save'])
    ->name('assessments.save');
