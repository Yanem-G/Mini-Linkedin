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
        $message = sprintf(
            "[%s] Candidat: %s a postulé à l'offre: %s",
            now(),
            $candidature->profil->user->name,
            $candidature->offre->titre
        );

        // Enregistrement dans storage/logs/candidatures.log
        Log::channel('candidatures_file')->info($message);
    }


//        event(new CandidatureDeposee($candidature));
}
