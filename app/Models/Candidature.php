<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['offre_id', 'profil_id', 'status','message'])]
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
