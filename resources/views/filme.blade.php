<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $filme->name }}</title>

    @env('local')
        @vite(['resources/css/app.css', 'resources/css/filme.css', 'resources/js/app.js', 'resources/js/filme.js'])
    @else
        <link rel="stylesheet" href="{{ asset('/build/assets/app.css') }}">
        <link rel="stylesheet" href="{{ asset('/build/assets/filme.css') }}">
        <script src="{{ asset('/build/assets/app2.js') }}"></script>
        <script src="{{ asset('/build/assets/filme2.js') }}"></script>
    @endenv

    {{-- @env("local")
        @vite(['resources/css/app.css', 'resources/css/filme.css', 'resources/js/filme.js', 'resources/js/app.js'])
    @endenv

    @production
        <link rel="stylesheet" href="/build/assets/app-DQux0av7.css">
        <link rel="stylesheet" href="/build/assets/filme-CQqhzZ2_.css">
        <script src="/build/assets/app-Xaw6OIO1.js"></script>
        <script src="/build/assets/filme-CSITiNQE.js"></script>
    @endproduction --}}

    {{-- <link rel="stylesheet" href="/build/assets/app.css">
    <link rel="stylesheet" href="/build/assets/filme.css">
    <script src="/build/assets/app2.js"></script>
    <script src="/build/assets/filme2.js"></script> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    {{-- <h1 class="mt-2 text-center">Assita agora: {{ $filme->name }}</h1> --}}
    <div id="video-box">
        <div id="all-controls">
            <div class="w-100 position-absolute pt-2 px-3 d-flex justify-content-between align-items-center" style="z-index: 3">
                <a href="{{ $backUrl }}" id="back">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="gap-3" id="dropdowns" style="display: flex">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if ($filme->audios[0]->name)
                                {{ $filme->audios[0]->name }}
                            @else
                                Áudio 1
                            @endif
                        </button>
                        <ul class="dropdown-menu" id="dropdown-audios" style="z-index: 3">
                            @for ($i=0;$i<count($filme->audios);$i++)
                                @php $audio = $filme->audios[$i]; @endphp
                                @if ($audio->name)
                                    <li data-hash="{{ $audio->hash }}"><a class="dropdown-item @php if ($i == 0) { echo "active"; } @endphp" href="#">{{ $audio->name }}</a></li>
                                @else
                                    <li data-hash="{{ $audio->hash }}"><a class="dropdown-item @php if ($i == 0) { echo "active"; } @endphp" href="#">Áudio {{ $i + 1 }}</a></li>
                                @endif
                            @endfor
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $isLegendaWritted = false;
                                $whoActive = -2;

                                for ($i=0;$i<count($filme->legendas);$i++) {
                                    $legenda = $filme->legendas[$i];

                                    if ($legenda->isForced) {
                                        if ($legenda->name) {
                                            echo $legenda->name;
                                        } else {
                                            echo "Legenda " . $i + 1;
                                        }
                                        $isLegendaWritted = true;
                                        $whoActive = $i;
                                        break;
                                    }
                                }

                                if (!$isLegendaWritted) {
                                    $whoActive = -1;
                                    echo "Sem Legenda";
                                }
                            @endphp
                        </button>
                        <ul class="dropdown-menu" id="dropdown-legendas">
                            <li data-hash="0"><a class="dropdown-item @php if ($whoActive == -1) { echo "active"; } @endphp" href="#">Sem Legenda</a></li>
                            @for ($i=0;$i<count($filme->legendas);$i++)
                                @php $legenda = $filme->legendas[$i]; @endphp
                                @if ($legenda->name)
                                    <li data-hash="{{ $legenda->hash }}"><a class="dropdown-item @php if ($whoActive == $i) { echo "active"; } @endphp" href="#">{{ $legenda->name }}</a></li>
                                @else
                                    <li data-hash="{{ $legenda->hash }}"><a class="dropdown-item @php if ($whoActive == $i) { echo "active"; } @endphp" href="#">Legenda {{ $i + 1 }}</a></li>
                                @endif
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>
            <div class="controls">
                <div class="pause_icon" style="cursor: pointer">
                    <i style="display: none" class="bi bi-play-fill"></i>
                    <div class="flex-column align-items-center" style="z-index: 1" id="waiting">
                        <img style="border-radius: 10px;width: 30vw" src="/loading.gif" alt="Loading do vídeo">
                        <p style="color: white" class="text-center fw-bold fs-2">Carregando...</p>
                    </div>
                </div>
            </div>
            <div class="w-100 position-absolute d-flex align-items-center gap-3 px-3 pb-1" style="bottom: 0">
                <div id="little_pause">
                    <i class="bi bi-play-fill"></i>
                </div>
                <p class="mb-1" style="color: white;" id="progress-time-counter">00:00</p>
                <div id="progress-container" class="flex-grow-1">
                    <div id="progress-bar"></div>
                    <div id="progress-buffer"></div>
                </div>
                <p class="mb-1" style="color: white;" id="remaining-time">00:00</p>
                <div id="fullscreen">
                    <i class="bi bi-fullscreen"></i>
                </div>
            </div>
        </div>
        <video id="videoPlayer" src="/green-day.mp4" muted>
            @foreach ($filme->legendas as $legenda)
                @if ($legenda->isForced)
                    <track default kind="captions" src="/api/{{ $legenda->hash }}/legenda" />
                @endif
            @endforeach
            {{-- <track default kind="captions" src="/api/547f253e-9a84-41cb-abc9-5d6056bbb478/legenda" /> --}}
            {{-- <track default kind="captions" src="/legenda/legenda.vtt" style="z-index: 5" /> --}}
        </video>
        <div>
            <audio id="audioPlayer" src="/green-day.mp3" class="d-none"></audio>
        </div>
    </div>
    <script>
        //const videoPlayer = document.getElementById('videoPlayer');
        //const videoUrl = '/movie/output.m3u8';
        //const videoUrl = '/audios_hls/output.m3u8'

        function hls_video(videoUrl) {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(videoUrl);
                hls.attachMedia(videoPlayer);
            } else if (videoPlayer.canPlayType('application/vnd.apple.mpegurl')) {
                videoPlayer.src = videoUrl;
            }
        }

        hls_video('/api/{{ $filme->hash }}/video/hls')
    </script>

    <script>
        //const audioUrl = '/audios_hls2/output.m3u8'; // URL da playlist HLS

        function hls_audio(audioUrl) {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(audioUrl);
                hls.attachMedia(audioPlayer);
            } else if (audioPlayer.canPlayType('application/vnd.apple.mpegurl')) {
                audioPlayer.src = audioUrl;
            }

            audioPlayer.currentTime = videoPlayer.currentTime
        }

        hls_audio('/api/{{ $filme->audios[0]->hash }}/audio/hls')
    </script>

    {{-- <script>
        const videoPlayer = document.getElementById('videoPlayer');

    </script> --}}
</body>
</html>
