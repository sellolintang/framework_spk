<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\WebLoginController;

//Public Pages

// Public Pages
Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/pengumuman', function () {
    return view('public.results', [
        'title' => 'Pengumuman Hasil Seleksi - Duta PNJ',
    ]);
})->name('public.results');

Route::get('/registration', function () {
    return view('public.registration');
})->name('registration');

Route::get('/registration-success', function () {
    return view('public.registration-success');
})->name('registration.success');

Route::get('/login', [WebLoginController::class, 'create'])->name('login');
Route::post('/login', [WebLoginController::class, 'store'])->name('login.store');

Route::post('/logout', [WebLoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


//Admin Pages

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard', [
            'title' => 'Dashboard Admin - Duta PNJ',
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/periods', function () {
        return view('admin.periods.index', [
            'title' => 'Periode Pemilihan - Admin',
        ]);
    })->name('admin.periods.index');

    Route::get('/admin/candidates', function () {
        return view('admin.candidates.index', [
            'title' => 'Data Pendaftar - Admin',
        ]);
    })->name('admin.candidates.index');

    Route::get('/admin/criteria', function () {
        return view('admin.criteria.index', [
            'title' => 'Kriteria Penilaian - Admin',
        ]);
    })->name('admin.criteria.index');

    Route::get('/admin/juries', function () {
        return view('admin.juries.index', [
            'title' => 'Data Juri - Admin',
        ]);
    })->name('admin.juries.index');

    Route::get('/admin/juries/create', function () {
        return view('admin.juries.create', [
            'title' => 'Tambah Juri - Admin',
        ]);
    })->name('admin.juries.create');

    Route::get('/admin/juries/assign-criteria', function () {
        return view('admin.juries.assign-criteria', [
            'title' => 'Pembagian Kriteria Juri - Admin',
        ]);
    })->name('admin.juries.assign-criteria');

    Route::get('/admin/juries/{jury}', function ($jury) {
        return view('admin.juries.show', [
            'title' => 'Detail Juri - Admin',
            'juryId' => $jury,
        ]);
    })->name('admin.juries.show');

    Route::get('/admin/juries/{jury}/edit', function ($jury) {
        return view('admin.juries.edit', [
            'title' => 'Edit Juri - Admin',
            'juryId' => $jury,
        ]);
    })->name('admin.juries.edit');

    Route::get('/admin/interviews', function () {
        return view('admin.interviews.index', [
            'title' => 'Jadwal Wawancara - Admin',
        ]);
    })->name('admin.interviews.index');

    Route::get('/admin/interviews/create', function () {
        return view('admin.interviews.create', [
            'title' => 'Tambah Jadwal Wawancara - Admin',
        ]);
    })->name('admin.interviews.create');

    Route::get('/admin/interviews/{interview}', function ($interview) {
        return view('admin.interviews.show', [
            'title' => 'Detail Jadwal Wawancara - Admin',
            'interviewId' => $interview,
        ]);
    })->name('admin.interviews.show');

    Route::get('/admin/interviews/{interview}/edit', function ($interview) {
        return view('admin.interviews.edit', [
            'title' => 'Edit Jadwal Wawancara - Admin',
            'interviewId' => $interview,
        ]);
    })->name('admin.interviews.edit');

    Route::get('/admin/monitoring', function () {
        return view('admin.monitoring.index', [
            'title' => 'Monitoring Penilaian - Admin',
        ]);
    })->name('admin.monitoring.index');

    Route::get('/admin/aras', function () {
        return view('admin.aras.index', [
            'title' => 'Perhitungan ARAS - Admin',
        ]);
    })->name('admin.aras.index');

    Route::get('/admin/announcements', function () {
        return view('admin.announcements.index', [
            'title' => 'Pengumuman Hasil - Admin',
        ]);
    })->name('admin.announcements.index');

});


//Jury Pages

Route::middleware(['auth', 'role:juri'])->group(function () {

    Route::get('/jury/dashboard', function () {
        return view('jury.dashboard', [
            'title' => 'Dashboard Juri - Duta PNJ',
        ]);
    })->name('jury.dashboard');

    Route::get('/jury/scoring', function () {
        return view('jury.scoring.index', [
            'title' => 'Penilaian Kandidat - Juri',
        ]);
    })->name('jury.scoring.index');

    Route::get('/jury/scoring/{candidate}', function ($candidate) {
        return view('jury.scoring.detail', [
            'title' => 'Detail Kandidat - Juri',
            'candidateId' => $candidate,
        ]);
    })->name('jury.scoring.detail');

    Route::get('/jury/scoring/{candidate}/form', function ($candidate) {
        return view('jury.scoring.form', [
            'title' => 'Form Penilaian Kandidat - Juri',
            'candidateId' => $candidate,
        ]);
    })->name('jury.scoring.form');

    Route::get('/jury/history', function () {
        return view('jury.history.index', [
            'title' => 'Riwayat Penilaian - Juri',
        ]);
    })->name('jury.history.index');

    Route::get('/jury/history/{candidate}', function ($candidate) {
        return view('jury.history.show', [
            'title' => 'Detail Riwayat Penilaian - Juri',
            'candidateId' => $candidate,
        ]);
    })->name('jury.history.show');

});
