<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $filme->name }}</title>

    @env("local")
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endenv
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/7.15.4/video.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-playlist/5.0.0/videojs-playlist.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>


</head>
<body>
    <h1 class="mt-2 text-center">Assita agora: {{ $filme->name }}</h1>
    <video id="videoPlayer" controls>
        <track default kind="captions" src="/legenda/legenda.vtt" />
    </video>
    <audio id="audioPlayer" controls></audio>
    <button id="playButton">Play</button>
    <p id="duration">asdasdasd</p>
    <p id="tempo-pulado">tempo pulado</p>

    <script>
        const videoPlayer = document.getElementById('videoPlayer');
        //const videoUrl = '/movie/output.m3u8';
        const videoUrl = '/api/{{ $filme->hash }}/video/hls'
        //const videoUrl = '/audios_hls/output.m3u8'

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(videoUrl);
            hls.attachMedia(videoPlayer);
        } else if (videoPlayer.canPlayType('application/vnd.apple.mpegurl')) {
            videoPlayer.src = videoUrl;
        }
    </script>

    <script>
        const audioPlayer = document.getElementById('audioPlayer');
        //const audioUrl = '/audios_hls2/output.m3u8'; // URL da playlist HLS
        const audioUrl = '/api/{{ $filme->audios[0]->hash }}/audio/hls'

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(audioUrl);
            hls.attachMedia(audioPlayer);
        } else if (audioPlayer.canPlayType('application/vnd.apple.mpegurl')) {
        // Fallback para navegadores que suportam HLS nativamente (Safari)
            audioPlayer.src = audioUrl;
        }
    </script>
    <script>
        const playButton = document.getElementById('playButton')
        const durationP = document.getElementById("duration")
        const tempoPulado = document.getElementById("tempo-pulado")

        let currentIndex = 0

        var videoDuration = 0;

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
        }

        function isBuffered(mediaElement, time) {
            for (let i=0;i<mediaElement.buffered.length;i++) {
                if (time >= mediaElement.buffered.start(i) && time <= mediaElement.buffered.end(i)) {
                    return true
                }
            }

            return false
        }

        videoPlayer.addEventListener('loadedmetadata', () => {
            let duration = videoPlayer.duration; // Duração total em segundos
            videoDuration = formatTime(duration); // Formata a duração total
            durationP.innerHTML = `Tempo total: ${videoDuration}`;
        });

        videoPlayer.addEventListener('seeked', async () => {
            const currentTime = videoPlayer.currentTime;
            audioPlayer.currentTime = currentTime

            videoPlayer.pause();
            audioPlayer.pause();

            while (!isBuffered(videoPlayer, currentTime) || !isBuffered(audioPlayer, currentTime)) {
                console.log("Esperando Juntar Vídeo/Audio!")
                await new Promise(resolve => setTimeout(resolve, 100))
            }

            videoPlayer.play();
            audioPlayer.play();

            const formattedTime = formatTime(currentTime);
            const formattedDuration = formatTime(videoPlayer.duration);
            tempoPulado.innerHTML = `Tempo atual: ${formattedTime}`;
        });

        videoPlayer.addEventListener('play', () => {
            audioPlayer.play()
        })

        videoPlayer.addEventListener('pause', () => {
            audioPlayer.pause()
        })

        playButton.addEventListener("click", e => {
            videoPlayer.play()
            let buffer = isBuffered(videoPlayer)
            console.log(buffer)
        })
    </script>
</body>
</html>
