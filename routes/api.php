<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\AdminController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('offres', [OffreController::class, 'index']);
    Route::get('offres/{offre}', [OffreController::class, 'show']);

    Route::middleware('role:recruteur')->group(function () {
        Route::post('offres', [OffreController::class, 'store']);
        Route::put('offres/{offre}', [OffreController::class, 'update']);
        Route::delete('offres/{offre}', [OffreController::class, 'destroy']);

        Route::get('offres/{offre}/candidatures', [CandidatureController::class, 'candidatures']);
        Route::put('candidatures/{candidature}/status', [CandidatureController::class, 'status']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('users', [AdminController::class, 'index']);
        Route::delete('users/{user}', [AdminController::class, 'supprimerCompte']);
        Route::put('offres/{offre}/activer', [AdminController::class, 'setOffre']);
    });

    Route::middleware('role:Candidat')->group(function () {
        Route::post('profil', [ProfilController::class, 'store']);
        Route::put('profil', [ProfilController::class, 'update']);
        Route::get('profil', [ProfilController::class, 'show']);

        Route::post('profil/competences', [ProfilController::class, 'addCompetence']);
        Route::delete('profil/competences/{competence}', [ProfilController::class, 'removeCompetence']);

        Route::post('candidater/{offre}', [CandidatureController::class, 'candidater']);
        Route::get('mesCandidatures', [CandidatureController::class, 'mesCandidatures']);
    });

});
