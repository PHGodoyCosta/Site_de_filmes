const playButton = document.getElementById('playButton')
const durationP = document.getElementById("duration")
const tempoPulado = document.getElementById("tempo-pulado")
const pause_div = document.querySelector(".pause_icon")
const allControls = document.getElementById("all-controls")
const progressContainer = document.querySelector("#progress-container");
const progressBar = document.querySelector("#progress-bar");
const progressBuffer = document.getElementById("progress-buffer")
const fullscreen = document.getElementById("fullscreen")
const videoBox = document.getElementById('video-box');
const littlePause = document.getElementById("little_pause")
const controls = document.querySelector(".controls")
const remainingTime = document.getElementById("remaining-time")
const waiting = document.getElementById("waiting")
const dropdowns = document.getElementById("dropdowns")

let currentIndex = 0
let hideTimeout;
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

function changePlayStatus(status="") {
    let i = pause_div.children[0]
    let i_2 = littlePause.children[0]

    if (videoPlayer.paused || status == "play") {
        i.className = "bi bi-pause-fill"
        i_2.className = "bi bi-pause-fill"
        videoPlayer.play()
    } else if (!videoPlayer.paused || status == "pause") {
        i.className = "bi bi-play-fill"
        i_2.className = "bi bi-play-fill"
        videoPlayer.pause()
    }
}

function waitingMode(mode="waiting") {
    if (mode == "waiting") {
        pause_div.children[0].style.display = "none"
        dropdowns.style.display = "none"
        videoBox.style.backgroundColor = "black"
        waiting.style.display = "flex"
    } else if (mode == "play") {
        dropdowns.style.display = "flex"
        waiting.style.display = "none"
        videoBox.style.backgroundColor = "transparent"
        pause_div.children[0].style.display = "block"
    }
}

function showControls() {
    allControls.style.opacity = "1"; // Exibe os elementos
    clearTimeout(hideTimeout);
    hideTimeout = setTimeout(hideControls, 3000); // Esconde após 3 segundos sem atividade
}

function hideControls() {
    if (!videoPlayer.paused) { // Somente esconde se o vídeo estiver rodando
        allControls.style.opacity = "0";
    }
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

    changePlayStatus("play")
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

videoPlayer.addEventListener('waiting', () => {
    waitingMode("waiting")
    console.log("Waiting")
})

videoPlayer.addEventListener('playing', () => {
    console.log("Playing")
})

videoPlayer.addEventListener('canplay', () => {
    waitingMode("play")
    console.log("Can Play!")
})

videoPlayer.addEventListener("timeupdate", () => {
    if (!videoPlayer.duration || videoPlayer.readyState < 2) return;

    const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100;

    // Pegar a última parte carregada (buffer final)
    let bufferedEnd = 0;
    if (videoPlayer.buffered.length > 0) {
        bufferedEnd = videoPlayer.buffered.end(videoPlayer.buffered.length - 1);
    }

    // Evitar mostrar tempo além do carregado
    const remainingTimeNumber = Math.max(0, bufferedEnd - videoPlayer.currentTime);

    remainingTime.innerHTML = formatTime(Math.floor(remainingTimeNumber));
    progressBar.style.width = `${progress}%`;
    // const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100
    // const remainingTimeNumber = videoPlayer.duration - videoPlayer.currentTime
    // remainingTime.innerHTML = formatTime(remainingTimeNumber.toFixed(0))
    // progressBar.style.width = `${progress}%`
})

videoPlayer.addEventListener("progress", () => {
    if (videoPlayer.buffered.length > 0) {
        const bufferedEnd = videoPlayer.buffered.end(videoPlayer.buffered.length - 1);
        const bufferedPercent = (bufferedEnd / videoPlayer.duration) * 100;
        progressBuffer.style.width = `${bufferedPercent}%`;
    }
})

progressContainer.addEventListener("click", (e) => {
    const rect = progressContainer.getBoundingClientRect();
    const offsetX = e.clientX - rect.left;
    const percentage = offsetX / rect.width;
    //alert(percentage)
    videoPlayer.currentTime = videoPlayer.duration * percentage;
});

playButton.addEventListener("click", e => {
    videoPlayer.play()
    let buffer = isBuffered(videoPlayer)
})

//pause_div.addEventListener("click", changePlayStatus)
littlePause.addEventListener("click", changePlayStatus)
//videoBox.addEventListener("click", changePlayStatus)
controls.addEventListener("click", changePlayStatus)

fullscreen.addEventListener("click", () => {
    if (document.fullscreenElement !== null) {
        document.exitFullscreen()
    } else {
        videoBox.requestFullscreen()
    }
})

document.addEventListener("mousemove", showControls);

document.addEventListener("touchstart", showControls);

document.getElementById('fullscreen').addEventListener('click', function () {
    if (!document.fullscreenElement) {
        // Entrar no modo de tela cheia
        if (videoBox.requestFullscreen) {
            videoBox.requestFullscreen();
        } else if (videoBox.mozRequestFullScreen) { // Firefox
            videoBox.mozRequestFullScreen();
        } else if (videoBox.webkitRequestFullscreen) { // Chrome, Safari e Opera
            videoBox.webkitRequestFullscreen();
        } else if (videoBox.msRequestFullscreen) { // IE/Edge
            videoBox.msRequestFullscreen();
        }
    } else {
        // Sair do modo de tela cheia
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari e Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    }
});
