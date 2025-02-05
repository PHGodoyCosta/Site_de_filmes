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
const dropdownAudios = document.getElementById("dropdown-audios")
const dropdownLegendas = document.getElementById("dropdown-legendas")

let currentIndex = 0
let hideTimeout;
var videoDuration = 0;

function formatTime(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = Math.floor(seconds % 60);

    if (hours > 0) {
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    } else {
        return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    }
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
        console.log("PLAY!")
        i.className = "bi bi-pause-fill"
        i_2.className = "bi bi-pause-fill"
        videoPlayer.play()
    } else if (!videoPlayer.paused || status == "pause") {
        console.log("PAUSE!")
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
        //videoBox.style.backgroundColor = "transparent"
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

function activeFullScreen() {
    if (document.fullscreenElement !== null) {
        document.exitFullscreen()
    } else {
        videoBox.requestFullscreen()
    }
}

videoPlayer.addEventListener('loadedmetadata', () => {
    let duration = videoPlayer.duration; // Duração total em segundos
    videoDuration = formatTime(duration); // Formata a duração total
});

videoPlayer.addEventListener('seeked', async () => {
    const currentTime = videoPlayer.currentTime;
    audioPlayer.currentTime = currentTime

    audioPlayer.muted = false

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
});

videoPlayer.addEventListener('play', () => {
    audioPlayer.play()
})

videoPlayer.addEventListener('pause', () => {
    audioPlayer.pause()
})

videoPlayer.addEventListener('waiting', () => {
    waitingMode("waiting")
})

// videoPlayer.addEventListener('playing', () => {
//     console.log("Playing")
// })

videoPlayer.addEventListener('canplay', () => {
    remainingTime.innerHTML = formatTime(Math.floor(videoPlayer.duration))
    waitingMode("play")
})

videoPlayer.addEventListener("timeupdate", () => {
    if (!videoPlayer.duration || videoPlayer.readyState < 2) return;

    const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100;
    progressBar.style.width = `${progress}%`;

    // Atualizar o tempo restante
    const remainingTimeNumber = videoPlayer.duration - videoPlayer.currentTime;
    remainingTime.innerHTML = formatTime(Math.floor(remainingTimeNumber));
});

videoPlayer.addEventListener("progress", () => {
    if (videoPlayer.buffered.length > 0) {
        let totalBuffered = 0;
        for (let i = 0; i < videoPlayer.buffered.length; i++) {
            totalBuffered += videoPlayer.buffered.end(i) - videoPlayer.buffered.start(i);
        }
        const bufferedPercent = (totalBuffered / videoPlayer.duration) * 100;
        progressBuffer.style.width = `${bufferedPercent}%`;
    }
});

progressContainer.addEventListener("click", (e) => {
    audioPlayer.muted = true
    const rect = progressContainer.getBoundingClientRect();
    const offsetX = e.clientX - rect.left;
    const percentage = offsetX / rect.width;
    //alert(percentage)
    videoPlayer.currentTime = videoPlayer.duration * percentage;
});

//pause_div.addEventListener("click", changePlayStatus)
littlePause.addEventListener("click", changePlayStatus)
//videoBox.addEventListener("click", changePlayStatus)
controls.addEventListener("click", changePlayStatus)
controls.addEventListener("dblclick", activeFullScreen)

fullscreen.addEventListener("click", activeFullScreen)

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

window.addEventListener("load", e => {
    let isWaitingForCanPlay = false

    audioPlayer.addEventListener("canplay", e => {
        if (isWaitingForCanPlay) {
            audioPlayer.currentTime = videoPlayer.currentTime
            waitingMode("play")
            if (videoPlayer.paused) {
                changePlayStatus("play")
            }

            isWaitingForCanPlay = false
        }
    })

    for (let i=0;i<dropdownAudios.children.length;i++) {
        let item = dropdownAudios.children[i]

        item.addEventListener("click", async () => {
            const currentTime = videoPlayer.currentTime;
            dropdownAudios.parentElement.children[0].innerHTML = item.textContent
            if (!videoPlayer.paused) {
                console.log("TROCOU AUDIO COM VÍDEO LIGADO")
                changePlayStatus("pause")
            }
            isWaitingForCanPlay = true
            waitingMode("waiting")
            hls_audio(`/api/${item.dataset.hash}/audio/hls`)

            while (!isBuffered(videoPlayer, currentTime) || !isBuffered(audioPlayer, currentTime)) {
                console.log("Esperando Juntar Vídeo/Audio!")
                await new Promise(resolve => setTimeout(resolve, 100))
            }
        })
    }
})
