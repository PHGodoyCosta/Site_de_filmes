<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OneDriveController;

class FilmeController extends Controller {

    public function __invoke() {
        //$one_drive = new OneDriveController();

        //$access_token = $one_drive->refresh_token();
        //$one_drive->temp();

        return view("filme");
    }
}
