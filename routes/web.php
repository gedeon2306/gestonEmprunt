<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\EntrepriseController;

Route::get('/', function () {
    return view('welcome');
})->name('accueil');

Route::resource('employers', EmployerController::class);
Route::resource('emprunts', EmpruntController::class);
Route::resource('entreprises', EntrepriseController::class);

Route::patch('employers/{id}/reset', [EmployerController::class, 'reset'])->name('employers.reset');
Route::patch('employers/{id}/migreEmployer', [EmployerController::class, 'migreEmployer'])->name('employers.migreEmployer');
Route::patch('employers/{id}/disable', [EmployerController::class, 'disable'])->name('employers.disable');
Route::post('emprunts/verif', [EmpruntController::class, 'verif'])->name('emprunts.verif');
Route::post('emprunts/confirm', [EmpruntController::class, 'confirm'])->name('emprunts.confirm');
Route::get('emprunts/{id}/redirection/{montant?}', [EmpruntController::class, 'redirection'])->name('emprunts.redirection');
Route::get('emprunts/{id}/success/{montant}', [EmpruntController::class, 'success'])->name('emprunts.success');