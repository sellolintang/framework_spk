<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


//Admin Pages

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'title' => 'Dashboard Admin - Duta PNJ',
        ]);
    })->name('dashboard');

    Route::get('/periods', function () {
        return view('admin.periods.index', [
            'title' => 'Manajemen Periode Seleksi - Duta PNJ',
        ]);
    })->name('periods.index');

    Route::get('/candidates', function () {
        return view('admin.candidates.index', [
            'title' => 'Data Pendaftar - Duta PNJ',
        ]);
    })->name('candidates.index');

    Route::get('/criteria', function () {
        return view('admin.criteria.index', [
            'title' => 'Manajemen Kriteria - Duta PNJ',
        ]);
    })->name('criteria.index');

    Route::get('/juries', function () {
        return view('admin.juries.index', [
            'title' => 'Akun Juri - Duta PNJ',
        ]);
    })->name('juries.index');

    Route::get('/juries/create', function () {
        return view('admin.juries.create', [
            'title' => 'Tambah Akun Juri - Duta PNJ',
        ]);
    })->name('juries.create');

    Route::get('/juries/assign-criteria', function () {
        return view('admin.juries.assign-criteria', [
            'title' => 'Assign Kriteria Juri - Duta PNJ',
        ]);
    })->name('juries.assign-criteria');

    Route::get('/juries/{jury}', function ($jury) {
        return view('admin.juries.show', [
            'title' => 'Detail Akun Juri - Duta PNJ',
            'juryId' => $jury,
        ]);
    })->name('juries.show');

    Route::get('/juries/{jury}/edit', function ($jury) {
        return view('admin.juries.edit', [
            'title' => 'Edit Akun Juri - Duta PNJ',
            'juryId' => $jury,
        ]);
    })->name('juries.edit');

    Route::get('/interviews', function () {
        return view('admin.interviews.index', [
            'title' => 'Jadwal Wawancara - Duta PNJ',
        ]);
    })->name('interviews.index');

    Route::get('/interviews/create', function () {
        return view('admin.interviews.create', [
            'title' => 'Generate Jadwal Wawancara - Duta PNJ',
        ]);
    })->name('interviews.create');

    Route::get('/interviews/{interview}', function ($interview) {
        return view('admin.interviews.show', [
            'title' => 'Detail Jadwal Wawancara - Duta PNJ',
            'interviewId' => $interview,
        ]);
    })->name('interviews.show');

    Route::get('/interviews/{interview}/edit', function ($interview) {
        return view('admin.interviews.edit', [
            'title' => 'Edit Jadwal Wawancara - Duta PNJ',
            'interviewId' => $interview,
        ]);
    })->name('interviews.edit');

    Route::get('/monitoring', function () {
        return view('admin.monitoring.index', [
            'title' => 'Monitoring Penilaian - Duta PNJ',
        ]);
    })->name('monitoring.index');

    Route::get('/aras', function () {
        return view('admin.aras.index', [
            'title' => 'Hasil ARAS - Duta PNJ',
        ]);
    })->name('aras.index');

    Route::get('/announcements', function () {
        return view('admin.announcements.index', [
            'title' => 'Publikasi Pengumuman - Duta PNJ',
        ]);
    })->name('announcements.index');
});


//Jury Pages

Route::prefix('jury')->name('jury.')->group(function () {
    Route::get('/dashboard', function () {
        return view('jury.dashboard', [
            'title' => 'Dashboard Juri - Duta PNJ',
        ]);
    })->name('dashboard');

    Route::get('/scoring', function () {
        return view('jury.scoring.index', [
            'title' => 'Penilaian Peserta - Duta PNJ',
        ]);
    })->name('scoring.index');

    Route::get('/scoring/{candidate}', function ($candidate) {
        return view('jury.scoring.detail', [
            'title' => 'Detail Peserta - Duta PNJ',
            'candidateId' => $candidate,
        ]);
    })->name('scoring.detail');

    Route::get('/scoring/{candidate}/form', function ($candidate) {
        return view('jury.scoring.form', [
            'title' => 'Form Penilaian Peserta - Duta PNJ',
            'candidateId' => $candidate,
        ]);
    })->name('scoring.form');

    Route::get('/history', function () {
        return view('jury.history.index', [
            'title' => 'Riwayat Penilaian - Duta PNJ',
        ]);
    })->name('history.index');

    Route::get('/history/{candidate}', function ($candidate) {
        return view('jury.history.show', [
            'title' => 'Detail Riwayat Penilaian - Duta PNJ',
            'candidateId' => $candidate,
        ]);
    })->name('history.show');
});
