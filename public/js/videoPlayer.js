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

const timeline = document.getElementById('timeline')

function incrementCurrentTime(){
    if(video.paused || currentTime>=video.duration) return// clearInterval(incrementCurrentTime)
    currentTimeElement.innerHTML = Math.floor(video.currentTime)
    const timelineRect = timeline.getBoundingClientRect()
    document.getElementById('timelineButton').style.left = (video.currentTime/video.duration)*(timelineRect.right-timelineRect.left)+"px"
    setTimeout(incrementCurrentTime, 1000)
}

// skip to time clicked on timeline:

timeline.addEventListener('click', (e)=>{
    // position of element "timeline"
    const timelineRect = timeline.getBoundingClientRect()
    // time in seconds to which we'll skip: ratio on timeline
    const skipTo = (e.clientX - timelineRect.left)/(timelineRect.right - timelineRect.left)*video.duration
    // skip to selected time in video
    video.currentTime = skipTo
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
