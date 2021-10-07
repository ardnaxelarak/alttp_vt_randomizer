<?php

namespace ALttP;

use ALttP\Seed;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

class Multiworld extends Model
{
    public static function boot()
    {
        parent::boot();

        $hasher = new Hashids('mw', 10);

        static::created(function ($seed) use ($hasher) {
            $seed->hash = $hasher->encode($seed->id);
            $seed->save();
        });
    }

    public function hashArray()
    {
        return hash_array($this->id);
    }

    public function seeds()
    {
        return $this->hasMany(Seed::class, 'multi_id');
    }
}
