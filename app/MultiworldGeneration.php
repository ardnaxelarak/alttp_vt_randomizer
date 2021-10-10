<?php

namespace ALttP;

use ALttP\Seed;
use Illuminate\Database\Eloquent\Model;

class MultiworldGeneration extends Model
{
    public function multiworld()
    {
        return $this->belongsTo(Multiworld::class, 'multi_id');
    }
}

