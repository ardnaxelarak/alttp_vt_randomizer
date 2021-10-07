<?php

namespace ALttP;

use ALttP\Multiworld;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Seed extends Model
{
    public static function boot()
    {
        parent::boot();

        $hasher = new Hashids('vt', 10);

        static::created(function ($seed) use ($hasher) {
            $seed->hash = $hasher->encode($seed->id);
            $seed->save();
        });
    }

    public function hashArray()
    {
        return hash_array($this->id);
    }

    public function multiworld()
    {
        return $this->belongsTo(Multiworld::class, 'multi_id');
    }
}
