<?php

use App\Http\Controllers\LinkedinScraperController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PasswordGeneratorController;
use App\Http\Controllers\Api\VulnerabilityControllerApi;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\VulnerabilityController;
use App\Http\Controllers\ServerStatsController;

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

    // Vulnerability Scanner Routes
    Route::prefix('vulnerability')->group(function () {
        Route::get('/', [VulnerabilityController::class, 'index'])->name('vulnerability.index');
        Route::post('/scan', [VulnerabilityController::class, 'scan'])->name('vulnerability.scan');
        Route::get('/status/{scanId}', [VulnerabilityController::class, 'status'])->name('vulnerability.status');
        Route::get('/export/{scanId}', [VulnerabilityController::class, 'exportReport'])->name('vulnerability.export');
    });
});

# Password Generator
Route::get('/password-generator', [PasswordGeneratorController::class, 'index'])->name('password-generator');
Route::post('/generate-password', [PasswordGeneratorController::class, 'generate'])->name('generate-password');
Route::post('/generate-bulk-passwords', [PasswordGeneratorController::class, 'generateBulk'])->name('generate-bulk-passwords');
Route::post('/validate-password', [PasswordGeneratorController::class, 'validatePassword'])->name('validate-password');

# Vulnerability Search
Route::get('/vulnerability-search', [VulnerabilityControllerApi::class, 'index'])->name('vulnerability-search');
Route::get('/api/vulnerabilities', [VulnerabilityControllerApi::class, 'search'])->name('api.vulnerabilities.search');
Route::get('/api/vulnerabilities/{cveId}', [VulnerabilityControllerApi::class, 'getVulnerability'])->name('api.vulnerabilities.show');
Route::get('/api/vulnerabilities/trends', [VulnerabilityControllerApi::class, 'getTrends'])->name('api.vulnerabilities.trends');
Route::get('/api/vulnerabilities/statistics', [VulnerabilityControllerApi::class, 'getStatistics'])->name('api.vulnerabilities.statistics');

# Vault
Route::middleware(['auth'])->group(function () {
    Route::get('/vault', [VaultController::class, 'index'])->name('vault.index');
    Route::get('/vault/create', [VaultController::class, 'create'])->name('vault.create');
    Route::post('/vault', [VaultController::class, 'store'])->name('vault.store');
    Route::get('/vault/{vault}', [VaultController::class, 'show'])->name('vault.show');
    Route::put('/vault/{vault}', [VaultController::class, 'update'])->name('vault.update');
    Route::delete('/vault/{vault}', [VaultController::class, 'destroy'])->name('vault.destroy');
    Route::post('/vault/search', [VaultController::class, 'search'])->name('vault.search');
    Route::post('/vault/export', [VaultController::class, 'export'])->name('vault.export');
    Route::post('/vault/import', [VaultController::class, 'import'])->name('vault.import');
    Route::get('/vault/security-audit', [VaultController::class, 'securityAudit'])->name('vault.security-audit');
});

# Linkedin Scraper
Route::get('/linkedin-search', [LinkedinScraperController::class, 'index'])->name('linkedin.index');
Route::post('/linkedin-search', [LinkedinScraperController::class, 'search'])->name('linkedin.search');

# Server Stats API
Route::get('/api/server-metrics', [ServerStatsController::class, 'getMetrics'])->name('api.server-metrics');

# Server Stats
Route::get('/server-stats', [ServerStatsController::class, 'index'])->name('server-stats.index');

require __DIR__ . '/auth.php';
