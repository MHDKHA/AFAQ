<?php

use App\Http\Controllers\AssesmentController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
});

use Inertia\Inertia;

Route::get('/hrbp-guide', function () {
    return Inertia::render('Landing');
});

use App\Http\Controllers\RegistrationController;


Route::middleware('web')->group(function () {
    Route::get('/register', [RegistrationController::class, 'index'])->name('registration.form');
    Route::post('/register', [RegistrationController::class, 'store'])->name('registration.store');

});

Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
    ->name('assessment.print');

Route::get('/assessment-reports/{record}/export-pdf', [ReportController::class, 'exportPdf'])
    ->name('assessment-reports.export-pdf')
    ->middleware(['auth']);


use App\Http\Controllers\AssessmentDashboardController;

Route::get('/assessment-dashboard/{assessment}', [AssessmentDashboardController::class, 'show'])
    ->name('assessment-dashboard.show');

Route::post('/assessment-dashboard/{assessment}/send-report', [AssessmentDashboardController::class, 'sendReport'])
    ->name('assessment-dashboard.send-report');


Route::post('/assessments/{id}/save', [AssessmentDashboardController::class, 'save'])->name('assessments.save');
