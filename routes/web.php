<?php

use Illuminate\Support\Facades\Route;
use App\Models\Filmes;
use App\Http\Controllers\FilmeController;
use App\Http\Controllers\OneDriveController;
use App\Models\Audios;
use App\Models\Legendas;
use Illuminate\Http\Response;

Route::get('/', function () {
    $filme = Filmes::all();

    return view("home", ["filmes" => $filme]);
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

Route::get("/api/{legenda}/legenda", function (string $legendaHash) {
    $legenda = Legendas::where("hash", $legendaHash)->firstOrFail();

    $one_drive = new OneDriveController();

    $legenda_content = $one_drive->downloadFile($legenda->file_id);

    //header("Content-Type: text/vtt; charset=UTF-8");

    //echo $legenda_content;
    return new Response($legenda_content, 200, [
        'Content-Type' => 'text/vtt; charset=UTF-8',
    ]);
    //http://127.0.0.1:8000/api/547f253e-9a84-41cb-abc9-5d6056bbb478/legenda
    //api/57f328fc-1142-44a2-a866-0b481d869b52/video/hls

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
