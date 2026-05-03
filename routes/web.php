<?php


use App\Livewire\HomePage;
use App\Livewire\SubscriptionPage;
use App\Models\Activity_logs;
use Illuminate\Support\Facades\Route;
use App\Livewire\ActivityLogsPage;
use App\Livewire\RegistrationPage;
use App\Livewire\ProfileSettings;
use App\Livewire\RegisterAccount;
use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use App\Livewire\SalesForm;
use App\Livewire\ActivityLogViewer;


// ridirecting the pages to main page
Route::redirect('/login', '/');
Route::redirect('/register', '/');
Route::redirect('/dashboard', '/homePage');


Route::get('/', function () {
   return redirect('/register-account');
})->name('home');

Route::get('/reports/viewpdf', [HomePage::class, 'viewPdf'])->name('reports.viewpdf');
Route::get('/reports/generatePdf', [HomePage::class, 'generatePdf'])->name('reports.generatepdf');
Route::get('/invoice/{id}/pdf', [HomePage::class, 'viewSinglePdf'])->name('invoice.pdf');

Route::middleware('guest')->group(function () {

 Route::get('/register-account', RegisterAccount::class)->name('register-account.page');
});

Route::middleware(['auth', 'verified'])->group(function () {

// Route::get('/activity-logs', ActivityLogsPage::class)->name('activity-logs.page');
Route::get('/homePage', HomePage::class)->name('home.page');
Route::get('/sales-form', SalesForm::class)->name('sales-form.page');
Route::get('/subscription', SubscriptionPage::class)->name('subscription.page');
// Route::get('/registration', RegistrationPage::class)->name('registration.page');
Route::get('/activity-log', ActivityLogViewer::class)->name('activity.log');
Route::get('/profile', ProfileSettings::class)->name('profile.page');
Route::get('/users', UserManagement::class)->middleware('permission:user.view')->name('user.page');
Route::get('/roles', RoleManagement::class)->middleware('permission:role.view')->name('role.page');

Route::get('/subscription-expired', function () {
    return view('errors.subscription-expired');
})->name('subscription.expired');


});
require __DIR__.'/settings.php';

