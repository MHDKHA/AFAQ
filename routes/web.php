<?php

use App\Http\Controllers\AssesmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/assessment/{assessment}/print', [AssesmentController::class, 'print'])
    ->name('assessment.print');
