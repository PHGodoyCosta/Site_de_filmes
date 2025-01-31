<?php

use Illuminate\Support\Facades\Route;
use App\Models\Filmes;
use App\Http\Controllers\FilmeController;

Route::get('/', function () {
    return view('home');
});

Route::get("/filme/{filme}", function (string $filmeHash) {
    $filme = Filmes::where("hash", $filmeHash)->firstOrFail();

    return view("filme", ["filme" => $filme]);
});

Route::get("/filme", FilmeController::class);
