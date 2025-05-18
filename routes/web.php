<?php

use App\Http\Controllers\AssesmentController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('welcome');
});


// Create a new route in your web.php
Route::get('/debug-shield', function () {
    $user = auth()->user();
    $data = [
        'user_id' => $user->id,
        'user_email' => $user->email,
        'roles' => $user->roles->pluck('name')->toArray(),
        'direct_permissions' => $user->getDirectPermissions()->pluck('name')->toArray(),
        'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        'can_view_any_assessment' => $user->can('view_any_assessment'),
        'can_view_any_assesment' => $user->can('view_any_assesment'),
        'policy_check' => app(\App\Policies\AssesmentPolicy::class)->viewAny($user),
        'request_path' => request()->path(),
        'route_name' => request()->route() ? request()->route()->getName() : null,
    ];

    return response()->json($data);
});



Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
    ->name('assessment.print');

Route::get('/assessment-reports/{record}/export-pdf', [ReportController::class, 'exportPdf'])
    ->name('assessment-reports.export-pdf')
    ->middleware(['auth']);
