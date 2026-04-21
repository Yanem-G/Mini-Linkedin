<?php

namespace App\Http\Controllers;

use App\Events\CandidatureDeposee;
use App\Events\StatutCandidatureMis;
use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    //
    public function Candidater(Request $request,$offreId){
        $offre = Offre::findOrFail($offreId);

        $validate = $request->validate([
            'message' => 'nullable|string',
        ]);

        $profil = Auth::user()->profile;
        if (!$profil) {
            return response()->json(['message' => 'Vous devez créer un profil avant de postuler'], 400);
        }
        $candidature = Candidature::create([
            'offre_id' => $offre->id,
            'profil_id' => $profil->id,
            'message' => $request->message ?? null,
            'statut' => 'en attente',
        ]);

        event(new CandidatureDeposee($candidature));

        return response()->json(['message' => 'Candidature envoyée avec succès','candidature' => $candidature], 201);
    }


    public function mesCandidatures(Request $request){
        $candidatures = $request->user()->candidatures;
        return response()->json($candidatures);
    }



    public function candidatures(Request $request,$offre){
        $offres = $request->user()->offres()->findOrFail($offre);
        $candidature = $offres->candidatures;
        return response()->json(['message'=>'Candidature reçues :','candidature' => $candidature]);
    }

    public function status(Request $request,$candidature){
        $validate = $request->validate([
            'status' => 'required|in:En attente,Acceptée,Refusée',
        ]);
            $candidatures = Candidature::whereHas('offres', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->findOrFail($candidature);

        $ancienStatus = $candidatures->status;

        $candidatures->update(['status' => $validate['status']]);

        event(new StatutCandidatureMis($candidatures,$ancienStatus));


        return response()->json(['message'=>'Status mis à jour avec succès','candidature' => $candidatures]);

    }

}
