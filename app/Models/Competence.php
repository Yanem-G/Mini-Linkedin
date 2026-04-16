<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    //
    public function profils()
    {
        return $this->belongsToMany(Profil::class)
                    ->withPivot('niveau')
                    ->withTimestamps();
    }
}
