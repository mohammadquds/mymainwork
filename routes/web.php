<?php

use App\Models\Activity_logs;
use Illuminate\Support\Facades\Route;
use App\Livewire\ActivityLogsPage;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/activity-logs', ActivityLogsPage::class)->name('activity-logs');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
