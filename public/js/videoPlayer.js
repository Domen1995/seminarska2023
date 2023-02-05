const video = document.querySelector('video')

function togglePlay(){
    if(video.paused){
        video.play()
        setTimeout(incrementCurrentTime, 1000)
        document.getElementById('playIcon').innerHTML = "pause"
    }else{
        video.pause()
        //clearInterval(incrementCurrentTime)
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

const currentTimeElement = document.getElementById('currentTime')
//let totalTime = document.getElementById('vidLen').innerHTML

function incrementCurrentTime(){
    if(video.paused || currentTime>=video.duration) return// clearInterval(incrementCurrentTime)
    currentTimeElement.innerHTML = Math.floor(video.currentTime)
    setTimeout(incrementCurrentTime, 1000)
}

// skip to time clicked on timeline:
const timeline = document.getElementById('timeline')
timeline.addEventListener('click', (e)=>{
    // get x position relatively to timeline
    const x = e.clientX - timeline.getBoundingClientRect().left
    // skip to selected time in video
    video.currentTime = x
})

document.addEventListener('fullscreenchange', ()=>{
    if(document.fullscreenElement != null){
        document.getElementById('fullscreenIcon').innerHTML = "fullscreen_exit"
        document.getElementById('videoControlsContainer').style.backgroundColor = "#f3f3f3"
    }else{
        document.getElementById('fullscreenIcon').innerHTML = "fullscreen"
        document.getElementById('videoControlsContainer').style.background = "none"

    }
})
