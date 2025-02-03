<?php

use Illuminate\Support\Facades\Route;
use App\Models\Filmes;
use App\Http\Controllers\FilmeController;
use App\Http\Controllers\OneDriveController;
use App\Models\Audios;

Route::get('/', function () {
    return view('home');
});

Route::get("/filme", FilmeController::class);

Route::get("/filme/{filme}", function (string $filmeHash) {
    $filme = Filmes::where("hash", $filmeHash)->firstOrFail();

    return view("filme", ["filme" => $filme]);
});

Route::get("/api/{filme}/video/hls", function (string $filmeHash) {
    $filme = Filmes::where("hash", $filmeHash)->firstOrFail();

    $one_drive = new OneDriveController();

    $hlsContent = $one_drive->downloadFile($filme->hls_id);

    $file_names = $one_drive->extractTsFilesFromHls($hlsContent);

    $list_files_of_videos = $one_drive->listFiles($filme->folder_id);
    //var_dump($list_files_of_videos);

    foreach ($file_names as $file_name) {
        foreach($list_files_of_videos->value as $file_link) {
            if ($file_name == $file_link->name) {
                $hlsContent = str_replace($file_name, $file_link->{'@microsoft.graph.downloadUrl'}, $hlsContent);
            }
        }
    }

    header("Content-Type: application/vnd.apple.mpegurl");

    echo $hlsContent;
});

Route::get("/api/{audio}/audio/hls", function (string $audioHash) {
    $audio = Audios::where("hash", $audioHash)->firstOrFail();

    $one_drive = new OneDriveController();

    $hlsContent = $one_drive->downloadFile($audio->hls_id);

    $file_names = $one_drive->extractTsFilesFromHls($hlsContent);

    $list_files_of_videos = $one_drive->listFiles($audio->folder_id);
    //var_dump($list_files_of_videos);

    foreach ($file_names as $file_name) {
        foreach($list_files_of_videos->value as $file_link) {
            if ($file_name == $file_link->name) {
                $hlsContent = str_replace($file_name, $file_link->{'@microsoft.graph.downloadUrl'}, $hlsContent);
            }
        }
    }

    header("Content-Type: application/vnd.apple.mpegurl");

    echo $hlsContent;
});
