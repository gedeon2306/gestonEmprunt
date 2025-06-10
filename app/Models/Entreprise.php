<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable = [
        'nomEntreprise',
        'adresse',
        'telephone',
        'email',
    ];
}
