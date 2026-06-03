<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CriterionController;
use App\Http\Controllers\Api\JuryCriterionController;
use App\Http\Controllers\Api\InterviewController;

Route::get('/', function () {
    return view('public.home');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard', [
        'title' => 'Dashboard Admin - Duta PNJ',
    ]);
})->name('admin.dashboard');

Route::get('/admin/juries', function () {
    return view('admin.juries.index', [
        'title' => 'Akun Juri - Duta PNJ',
    ]);
})->name('admin.juries.index');

Route::get('/admin/juries/create', function () {
    return view('admin.juries.create', [
        'title' => 'Tambah Akun Juri - Duta PNJ',
    ]);
})->name('admin.juries.create');

Route::get('/admin/juries/{jury}/edit', function ($jury) {
    return view('admin.juries.edit', [
        'title' => 'Edit Akun Juri - Duta PNJ',
        'juryId' => $jury,
    ]);
})->name('admin.juries.edit');

Route::get('/admin/juries/assign-criteria', function () {
    return view('admin.juries.assign-criteria', [
        'title' => 'Assign Kriteria Juri - Duta PNJ',
    ]);
})->name('admin.juries.assign-criteria');

Route::get('/admin/criteria', function () {
    return view('admin.criteria.index', [
        'title' => 'Manajemen Kriteria - Duta PNJ',
    ]);
})->name('admin.criteria.index');

Route::get('/jury/dashboard', function () {
    return view('jury.dashboard');
})->name('jury.dashboard');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('criteria', CriterionController::class);
    Route::apiResource('jury-criteria', JuryCriterionController::class);
    Route::post('jury-criteria/sync', [JuryCriterionController::class, 'sync']);
    Route::apiResource('interviews', InterviewController::class);
    Route::post('criteria/sync', [CriterionController::class, 'sync']);
});

Route::get('/registration', function () {
    return view('public.registration');
})->name('registration');

Route::get('/registration-success', function () {
    return view('public.registration-success');
})->name('registration.success');
