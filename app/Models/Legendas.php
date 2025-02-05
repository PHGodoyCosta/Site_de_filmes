<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Filmes;

class Legendas extends Model
{
    protected $table = "legendas";

    public function filme() {
        return $this->belongsTo(Filmes::class);
    }
}
