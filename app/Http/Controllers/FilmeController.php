<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilmeController extends Controller {

    public function __invoke() {
        return view("filme");
    }
}
