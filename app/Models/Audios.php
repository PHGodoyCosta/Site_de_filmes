<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Filmes;

class Audios extends Model
{
    protected $table = "audios";

    public function filme() {
        return $this->belongsTo(Filmes::class);
    }
}
