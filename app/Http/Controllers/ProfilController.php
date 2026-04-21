<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    //
    public function store(Request $request)
    {
        if($request->user()->profile){
            return response()->json(['message' => 'profil existe deja'], 400);
        }
        $validate = $request->validate([
            'bio' => 'nullable|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
        ]);
        $profil = $request->user()->profile()->create($validate);
        return response()->json(['message' => 'profil créé avec succes','profil' => $profil], 201);
    }
    public function show(Request $request)
    {
        $profil = $request->user()->profile;
        return response()->json($profil);
    }
    public function update(Request $request)
    {
        $validate = $request->validate([
            'bio' => 'nullable|string',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
        ]);
        $profil = $request->user()->profile;
        $profil->update($validate);
        return response()->json(['message' => 'profil mis à jour avec succes','profil' => $profil]);
    }
    public function addCompetence(Request $request)
    {
        $request->validate([
            'competence_id' => 'required|exists:competences,id',
            'niveau' => 'nullable|string',
        ]);
        $profil = $request->user()->profile;
        $competence_id = $request->input('competence_id');
        $niveau = $request->input('niveau', 'Débutant');
        $profil->competences()->attach($competence_id, ['niveau' => $niveau]);
        return response()->json(['message' => 'Compétence ajoutée avec succès']);
    }
    public function removeCompetence(Request $request, $competence)
    {
        $profil = $request->user()->profile;
        $profil->competences()->detach($competence);
        return response()->json(['message' => 'Compétence supprimée avec succès']);
    }

    /* Pour parcourir les offres disponibles pour le candidat */

}
