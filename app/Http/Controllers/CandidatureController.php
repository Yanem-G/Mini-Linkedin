<?php

namespace App\Http\Controllers;

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
        $candidatures->update(['status' => $validate['status']]);
        return response()->json(['message'=>'Status mis à jour avec succès','candidature' => $candidatures]);
    }
}
