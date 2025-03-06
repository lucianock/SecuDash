<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PasswordGeneratorController;
use App\Http\Controllers\Api\VulnerabilityController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

# Password Generator
Route::get('/password-generator', [PasswordGeneratorController::class, 'index'])->name('password-generator');
Route::post('/generate-password', [PasswordGeneratorController::class, 'generate'])->name('generate-password');

# Vulneravility Search
Route::get('/vulnerability-search', [VulnerabilityController::class, 'index'])->name('vulnerability-search');
Route::get('/api/vulnerabilities', [VulnerabilityController::class, 'search'])->name('api.vulnerabilities.search');



require __DIR__ . '/auth.php';
