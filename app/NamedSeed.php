<?php

namespace ALttP;

use Illuminate\Database\Eloquent\Model;

class NamedSeed extends Model
{
    /** @var array */
    protected $fillable = [
        'name',
    ];
}
