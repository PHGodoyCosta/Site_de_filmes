<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Filmes;
use App\Models\Audios;
use App\Http\Controllers\OneDriveController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test', function() {
    $filme = Filmes::where("hash", "9d308500-9d3a-42f6-b2c9-b8d8c96a7055")->firstOrFail();
    $one_drive = new OneDriveController();

    //$hls_video = $one_drive->downloadFile($filme->hls_id);
    $hlsContent = <<<M3U
    #EXTM3U
    #EXT-X-VERSION:3
    #EXT-X-TARGETDURATION:65
    #EXT-X-MEDIA-SEQUENCE:0
    #EXTINF:64.523000,
    output0.ts
    #EXTINF:57.224000,
    output1.ts
    #EXTINF:58.684000,
    output2.ts
    #EXTINF:60.352000,
    output3.ts
    #EXTINF:61.936000,
    output4.ts
    #EXTINF:58.642000,
    output5.ts
    #EXTINF:60.269000,
    output6.ts
    #EXTINF:58.892000,
    output7.ts
    #EXTINF:61.854000,
    output8.ts
    #EXTINF:58.224000,
    output9.ts
    #EXTINF:60.186000,
    output10.ts
    #EXTINF:63.438000,
    output11.ts
    #EXTINF:56.598000,
    output12.ts
    #EXTINF:59.977000,
    output13.ts
    #EXTINF:59.351000,
    output14.ts
    #EXTINF:60.518000,
    output15.ts
    #EXTINF:61.312000,
    output16.ts
    #EXTINF:59.226000,
    output17.ts
    #EXTINF:58.933000,
    output18.ts
    #EXTINF:58.876000,
    output19.ts
    #EXT-X-ENDLIST
    M3U;

    $file_download_real_links = array();

    $file_names = $one_drive->extractTsFilesFromHls($hlsContent);

    $list_files_of_videos = $one_drive->listFiles($filme->folder_id);
    //var_dump($list_files_of_videos);

    foreach ($file_names as $file_name) {
        foreach($list_files_of_videos->value as $file_link) {
            if ($file_name == $file_link->name) {
                $hlsContent = str_replace($file_name, $file_link->{'@microsoft.graph.downloadUrl'}, $hlsContent);
                array_push($file_download_real_links, $file_link->{'@microsoft.graph.downloadUrl'});
            }
        }
    }

    var_dump($hlsContent);

    echo "\n";
});

Artisan::command("test2", function() {
    $filme = Filmes::where("hash", "9d308500-9d3a-42f6-b2c9-b8d8c96a7055")->first();
    $audio = Audios::where("id", 1)->first();

    var_dump($filme->audios[0]->hash);
    //echo $filme->name;

    echo "\n";
});
