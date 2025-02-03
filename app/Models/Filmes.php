<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Audios;

class Filmes extends Model
{
    protected $table = "filmes";

    public function audios() {
        return $this->hasMany(Audios::class, 'filme_id');
    }
}
