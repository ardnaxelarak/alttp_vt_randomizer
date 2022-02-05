<?php

namespace ALttP;

use ALttP\Seed;
use Illuminate\Database\Eloquent\Model;

class SeedGeneration extends Model
{
    public function seed()
    {
        return $this->belongsTo(Seed::class, 'seed_id');
    }
}


