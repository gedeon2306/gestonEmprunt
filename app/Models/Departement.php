<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = [
        'nomDepartement',
        'chefDepartement',
        'nbSalarier',
        'entreprise_id',
    ];
}
