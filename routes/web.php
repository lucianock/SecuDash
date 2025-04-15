<?php

use App\Http\Controllers\LinkedinScraperController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PasswordGeneratorController;
use App\Http\Controllers\Api\VulnerabilityController;
use App\Http\Controllers\VaultController;

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

# Vault
Route::middleware(['auth'])->group(function () {
    Route::get('/vault', [VaultController::class, 'index'])->name('vault.index');
    Route::get('/vault/create', [VaultController::class, 'create'])->name('vault.create');
    Route::post('/vault', [VaultController::class, 'store'])->name('vault.store');
    /* Route::delete('/vault/{vault}', [VaultController::class, 'destroy'])->name('vault.destroy');
    Route::get('vault/{vault}/edit', [VaultController::class, 'edit'])->name('vault.edit'); */
});

# Linkedin Scraper
Route::get('/linkedin-search', [LinkedinScraperController::class, 'index'])->name('linkedin.index');
Route::post('/linkedin-search', [LinkedinScraperController::class, 'search'])->name('linkedin.search');

require __DIR__ . '/auth.php';
