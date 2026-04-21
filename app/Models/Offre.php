<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['titre', 'description', 'location', 'type'])]
class Offre extends Model
{
    //
    use HasFactory;
    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class)
                    ->where('role','Recruteur');
    }
}
