<?php

namespace App\Http\Controllers;

use App\Events\CandidatureDeposee;
use App\Events\StatutCandidatureMis;
use Illuminate\Http\Request;

use App\Models\Offre;
use App\Models\Candidature;

class CandidatureController extends Controller
{
    //
    public function Candidater(Request $request,$offre){
        $offre = Offre::findOrFail($offre);

        $validate = $request->validate([
            'message' => 'nullable|string',
        ]);
        $candidature = $request->user()->candidatures()->create([
            'offre_id' => $offre->id,
            'message' => $validate['message'] ?? null,
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
