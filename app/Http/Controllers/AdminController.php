<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function supprimerCompte(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Compte supprimé avec succès']);
    }

    public function setOffre(Request $request, Offre $offre)
    {
        $validated = $request->validate([
            'actif' => 'required|in:Oui,Non'
        ]);

        $offre->update(['actif' => $validated['actif']]);
        
        return response()->json([
            'message' => $offre->actif=='Oui' ? 'Offre activée' : 'Offre désactivée',
            'offre' => $offre
        ]);
    }
}
