<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
        'nomComplet',
        'telephone',
        'genre',
        'seuil',
        'reste',
        'pin',
        'counter',
        'departement_id',
    ];
}
