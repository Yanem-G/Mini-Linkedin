<?php

namespace App\Listeners;

use App\Events\CandidatureDeposee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogCandiature
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CandidatureDeposee $event): void
    {
        $candidature = $event->candidature;

        $candidatNom = $candidature->profil->user->name ?? 'Inconnu';
        $offreTitre = $candidature->offre->titre ?? 'Offre supprimée';

        $message = sprintf(
            "[%s] Candidat: %s a postulé à l'offre: %s (ID Candidature: %d)",
            now()->format('Y-m-d H:i:s'),
            $candidatNom,
            $offreTitre,
            $candidature->id
        );

        Log::channel('candidatures_file')->info($message);
    }


//        event(new CandidatureDeposee($candidature));
}
