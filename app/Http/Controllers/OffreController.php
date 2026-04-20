<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OffreController extends Controller
{
    //
    public function index(Request $request){
            $validated = $request->validate([
            'location' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:CDI,CDD,Stage',
        ]);

        $query = $request->user()->offres();

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $validated['location'] . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $validated['type']);
        }

        $offres = $query->latest()->paginate(10);

        return response()->json($offres);
    }
    public function show(Request $request,$offre){
        if(!$request->user()->offres()->find($offre)){
            return response()->json(['message'=>'Offre n\'existe pas'],400);
        }
        return response()->json($offre);
    }
    public function store(Request $request){
        if($request->user()->offres){
            return response()->json(['message'=>'Offre existe déjà'],400);
        }
        $validate = $request->validate([
            'titre'=>'string|max:255',
            'description'=>'nullable|string',
            'location'=>'string|max:255',
            'type'=>'in:CDI,CDD,Stage',
        ]);
        $offre = $request->user()->offres()->create($validate);
        return response()->json(['message' => 'offre créé avec succes','profil' => $offre], 201);
    }
    public function update(Request $request,$offre){
        if(!$request->user()->offres()->find($offre)){
            return response()->json(['message'=>'Offre n\'existe pas'],400);
        }
        $validate = $request->validate([
            'titre'=>'string|max:255',
            'description'=>'text|nullable',
            'location'=>'string|max:255',
            'type'=>'in:CDI,CDD,Stage',
        ]);
        $offre->update($validate);
        return response()->json(['message' => 'offre mise à jour avec succes','profil' => $offre]);
    }
    public function destroy(Request $request,$offre){
        if(!$request->user()->offres()->find($offre)){
            return response()->json(['message'=>'Offre n\'existe pas'],400);
        }
        $offre->delete();
        return response()->json(['message'=>'offre supprimer avec succès']);
    }
}
