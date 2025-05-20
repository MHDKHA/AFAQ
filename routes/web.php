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

// Home
Route::get('/', function () {
    return view('welcome');
});

// HRBP Guide (Inertia Page)
Route::get('/hrbp-guide', function () {
    return Inertia::render('Landing');
});



Route::get('/pdf', function () {
    return view('product-survey-blade');
});

// Registration
Route::middleware('web')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('registration.form');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');
});


// Assessment
Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
    ->name('assessment.print');

Route::get('/assessment-reports/{record}/export-pdf', [ReportController::class, 'exportPdf'])
    ->name('assessment-reports.export-pdf')
    ->middleware(['auth']);

Route::get('/assessment-dashboard/{assessment}', [AssessmentDashboardController::class, 'show'])
    ->name('assessment-dashboard.show');

Route::post('/assessment-dashboard/{assessment}/send-report', [AssessmentDashboardController::class, 'sendReport'])
    ->name('assessment-dashboard.send-report');

Route::post('/assessments/{id}/save', [AssessmentDashboardController::class, 'save'])
    ->name('assessments.save');

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/{slug}/assessment', [ToolController::class, 'storeAssessment'])->name('tools.assessment.store');
Route::get('/tools/{slug}/report/{assessment}', [ToolController::class, 'report'])->name('tools.report');
