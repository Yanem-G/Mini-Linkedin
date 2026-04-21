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

    Route::middleware('role:Recruteur')->group(function () {
        Route::post('offres', [OffreController::class, 'store']);
        Route::put('offres/{offre}', [OffreController::class, 'update']);
        Route::delete('offres/{offre}', [OffreController::class, 'destroy']);

        Route::get('offres/{offre}/candidatures', [CandidatureController::class, 'candidatures']);
        Route::patch('candidatures/{candidature}/statut', [CandidatureController::class, 'status']);
    });

    Route::middleware('role:Admin')->group(function () { 
        Route::get('admin/users', [AdminController::class, 'index']);
        Route::delete('admin/users/{user}', [AdminController::class, 'supprimerCompte']);
        Route::patch('admin/offres/{offre}', [AdminController::class, 'setOffre']); 
    });

    Route::middleware('role:Candidat')->group(function () {
        Route::post('profil', [ProfilController::class, 'store']);
        Route::put('profil', [ProfilController::class, 'update']);
        Route::get('profil', [ProfilController::class, 'show']);

        Route::post('profil/competences', [ProfilController::class, 'addCompetence']);
        Route::delete('profil/competences/{competence}', [ProfilController::class, 'removeCompetence']);

        Route::post('offres/{offre}/candidater', [CandidatureController::class, 'candidater']);
        Route::get('mes-candidatures', [CandidatureController::class, 'mesCandidatures']);
    });

});