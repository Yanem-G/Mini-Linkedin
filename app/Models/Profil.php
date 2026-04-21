<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{

    use HasFactory;
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
    public function competences()
    {
        return $this->belongsToMany(Competence::class)
                    ->withPivot('niveau')
                    ->withTimestamps();

    }
}
