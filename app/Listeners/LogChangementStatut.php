<?php

namespace App\Listeners;

use App\Events\StatutCandidatureMis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogChangementStatut
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(StatutCandidatureMis $event): void
    {
        $message = sprintf(
            "[%s] Changement de statut - Offre ID: %d, Ancien: %s, Nouveau: %s",
            now(),
            $event->candidature->offre_id,
            $event->ancienStatut,
            $event->candidature->statut
        );

        Log::channel('candidatures_file')->info($message);
    }

//L'utilisation apres:
    //$ancienStatut = $candidature->statut;
    //$candidature->update([
    //'statut' => $request->statut
    //]);
    //event(new StatutCandidatureMis($candidature, $ancienStatut));
}
