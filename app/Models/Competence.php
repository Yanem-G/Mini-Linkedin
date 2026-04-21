<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name','categorie'])]
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
