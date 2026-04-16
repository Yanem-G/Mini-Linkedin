<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    //
    public function offres()
    {
        return $this->belongsTo(Offre::class);
    }
    public function profils()
    {
        return $this->belongsTo(Profil::class);
    }
}
