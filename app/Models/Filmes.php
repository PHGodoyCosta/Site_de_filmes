<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Audios;
use App\Models\Legendas;

class Filmes extends Model
{
    protected $table = "filmes";

    public function audios() {
        return $this->hasMany(Audios::class, 'filme_id');
    }

    public function legendas() {
        return $this->hasMany(Legendas::class, 'filme_id');
    }
}
