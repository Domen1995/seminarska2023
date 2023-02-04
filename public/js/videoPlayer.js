const video = document.querySelector('video')

function togglePlay(){
    if(video.paused){
        video.play()
        document.getElementById('playIcon').innerHTML = "pause"
    }else{
        video.pause()
        document.getElementById('playIcon').innerHTML = "play_circle"
    }
}

function toggleFullscreen(){
    if(document.fullscreenElement == null){
        const container = document.getElementById('videoMainContainer')
        container.requestFullscreen()
    }else{
        document.exitFullscreen()
    }
}
