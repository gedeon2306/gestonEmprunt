<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Middleware\CheckTel;

Route::get('/', function () {
    return view('welcome');
})->name('accueil');

// Gestion des entreprises
Route::resource('entreprises', EntrepriseController::class);

// Gestion des employers (Salariés)
Route::resource('employers', EmployerController::class);
Route::patch('employers/{id}/reset', [EmployerController::class, 'reset'])->name('employers.reset');
Route::patch('employers/{id}/migreEmployer', [EmployerController::class, 'migreEmployer'])->name('employers.migreEmployer');
Route::patch('employers/{id}/disable', [EmployerController::class, 'disable'])->name('employers.disable');

// Gestion des emprunts
Route::get('emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
Route::get('emprunts/logout', [EmpruntController::class, 'logout'])->name('emprunts.logout');
Route::post('emprunts/verif', [EmpruntController::class, 'verif'])->name('emprunts.verif');

Route::middleware(['CheckTel'])->group(function () {
    Route::post('emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
    Route::post('emprunts/confirm', [EmpruntController::class, 'confirm'])->name('emprunts.confirm');
    Route::get('emprunts/{id}/redirection/{montant?}', [EmpruntController::class, 'redirection'])->name('emprunts.redirection');
    Route::get('emprunts/{id}/success/{montant}', [EmpruntController::class, 'success'])->name('emprunts.success');
});