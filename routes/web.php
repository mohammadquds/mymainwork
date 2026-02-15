<?php

use App\Livewire\HomePage;
use App\Livewire\SubscriptionPage;
use App\Models\Activity_logs;
use Illuminate\Support\Facades\Route;
use App\Livewire\ActivityLogsPage;
use App\Livewire\RegistrationPage;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/activity-logs', ActivityLogsPage::class)->name('activity-logs');
Route::get('/homePage', HomePage::class)->name('home-page');
Route::get('/subscription', SubscriptionPage::class)->name('subscription-page');
Route::get('/registration', RegistrationPage::class)->name('registration-page');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
