<?php

use App\Http\Controllers\AssessmentResultsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AssesmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AssessmentDashboardController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// Home
Route::get('/', function () {
    return redirect()->route('tools.index');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Registration
Route::middleware('web')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('registration.form');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
});

// Tools (Dynamic Assessment Access)
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/assessment', [ToolController::class, 'storeAssessment'])->name('tools.assessment.store');
Route::get('/tools/{slug}/report/{assessment}', [ToolController::class, 'report'])->name('tools.report');

// Assessment
Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
    ->name('assessment.print');

Route::get('/assessment-reports/{record}/export-pdf', [ReportController::class, 'exportPdf'])
    ->name('assessment-reports.export-pdf')
    ->middleware(['auth']);

Route::get('/assessment-dashboard/{assessment}', [AssessmentDashboardController::class, 'show'])
    ->name('assessment-dashboard.show')
    ->middleware(['auth']);

Route::post('/assessment-dashboard/{assessment}/send-report', [AssessmentDashboardController::class, 'sendReport'])
    ->name('assessment-dashboard.send-report')
    ->middleware(['auth']);

Route::post('/assessments/{id}/save', [AssessmentDashboardController::class, 'save'])
    ->name('assessments.save');

// Protected dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');

    Route::get('/dashboard/assessments', function () {
        // Get user's assessments
        $assessments = auth()->user()->assesments()->with(['tool', 'items'])->latest()->get();

        // Add computed properties for each assessment
        $assessments->each(function ($assessment) {
            $assessment->available_count = $assessment->items->where('is_available', true)->count();
            $assessment->items_count = $assessment->items->count();
        });

        return Inertia::render('Dashboard/Assessments', [
            'assessments' => $assessments
        ]);
    })->name('dashboard.assessments');
});



// routes/web.php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;


// Home page
Route::get('/', function () {
    return Inertia::render('Home', [
        'tools' => \App\Models\Tool::where('is_active', true)->get(),
        'locale' => app()->getLocale()
    ]);
})->name('home');

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/start', [ToolController::class, 'startAssessment'])
    ->middleware('auth')
    ->name('assessment.start');

// Assessments
Route::middleware(['auth'])->group(function () {
    Route::get('/assessment/{assessment}/fill', [AssessmentController::class, 'fill'])
        ->name('assessment.fill');
    Route::post('/assessment/{assessment}/save', [AssessmentController::class, 'save'])
        ->name('assessment.save');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Subscription
    Route::get('/subscribe', [SubscriptionController::class, 'showOptions'])
        ->name('subscribe');
    Route::post('/subscribe/process', [SubscriptionController::class, 'process'])
        ->name('subscribe.process');
});

// Auth routes are handled by Laravel Fortify or Laravel Breeze
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// Tool routes
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
