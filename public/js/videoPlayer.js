
const video = document.querySelector('video')
video.volume = 0.6

let timeIntervalID // digital counter ID
let analogousCounterID // analogous counter ID
function togglePlay(){
    //const timeInterval = setInterval(incrementCurrentTime, 1000)
    if(video.paused){
        video.play()
        //setTimeout(incrementCurrentTime, 1000)
        const timeInterval = setInterval(incrementCurrentTime, 1000) // digital counter
        timeIntervalID = timeInterval
        const analogousCounter = setInterval(moveTimelineButton, 100)
        analogousCounterID = analogousCounter
        document.getElementById('playIcon').innerHTML = "pause"
    }else{
        video.pause()
        //clearTimeout(incrementCurrentTime)
        clearInterval(timeIntervalID)
        clearInterval(analogousCounterID)
        document.getElementById('playIcon').innerHTML = "play_circle"
    }
}

function toggleFullscreen(){
    if(document.fullscreenElement == null){
        const container = document.getElementById('videoMainContainer')
        container.requestFullscreen()
        // reset time skipping to prevent a bug
        /*disableTimeSkipping()
        enableTimeSkipping()*/
    }else{
        document.exitFullscreen()
        /*// reset time skipping to prevent a bug
        disableTimeSkipping()
        enableTimeSkipping()*/
    }
}

const currentTimeElement = document.getElementById('currentTime')
//let totalTime = document.getElementById('vidLen').innerHTML

let timeline = document.getElementById('timeline')

// digital counter:
function incrementCurrentTime(){
    if(/*video.paused ||*/ currentTime>video.duration) clearInterval(incrementCurrentTime)
    // calculate current time and insert it in form like 4:34
    currentTimeElement.innerHTML = secondsToMinutesSeconds(Math.floor(video.currentTime))
    //moveTimelineButton()
    //setTimeout(incrementCurrentTime, 1000)
}

// analogous counter:
function moveTimelineButton(){
    const timelineButton = document.getElementById('timelineButton')
    const timelineRect = timeline.getBoundingClientRect()
    timelineButton.style.left = (video.currentTime/video.duration)*(timelineRect.right-timelineRect.left)+"px"
    timelineButton.style.display = "inline"
}

setInterval(checkIdle, 10000)

const videoControlsContainer = document.getElementById('videoControlsContainer')

function checkIdle(){
    // if no interaction and video is playing, hide video controls
    if(!video.paused){
        videoControlsContainer.style.display = "none"
        document.getElementById('timelineButton').style.display = "none"
    }
}

document.getElementById('videoMainContainer').addEventListener('mousemove', ()=>{
    if(videoControlsContainer.style.display == "none") videoControlsContainer.style.display = "flex"
})

function secondsToMinutesSeconds(sec){
    const minutes = Math.floor(sec/60)
    let seconds = ""+(sec%60)
    if(seconds.length<2) seconds = "0"+seconds
    return minutes+":"+seconds
}

// skip to time clicked on timeline:
function enableTimeSkipping(){
    timeline = document.getElementById('timeline')
    timeline.addEventListener('click', (e)=>{
        // position of element "timeline"
        const timelineRect = timeline.getBoundingClientRect()
        console.log(timelineRect.right)
        console.log(e.clientX)
        // time in seconds to which we'll skip: ratio on timeline
        const skipTo = (e.clientX - timelineRect.left)/(timelineRect.right - timelineRect.left)*video.duration
        // skip to selected time in video
        video.currentTime = skipTo
        moveTimelineButton()
        // set time counter to the clicked time
        currentTimeElement.innerHTML = secondsToMinutesSeconds(Math.floor(skipTo))
    })
}

function disableTimeSkipping(){
    timeline.removeEventListener('click')
}
enableTimeSkipping()

document.addEventListener('fullscreenchange', ()=>{
    if(document.fullscreenElement != null){
        document.getElementById('fullscreenIcon').innerHTML = "fullscreen_exit"
        document.getElementById('videoControlsContainer').style.backgroundColor = "#f3f3f3"
    }else{
        document.getElementById('fullscreenIcon').innerHTML = "fullscreen"
        document.getElementById('videoControlsContainer').style.background = "none"
    }
    moveTimelineButton()
})

document.getElementById('volumeIcon').addEventListener('click', toggleChangingVolume)
//document.getElementById('videoControlsContainer').addEventListener('mouseout', hideChangingVolume)
document.addEventListener('click', hideChangingVolume)


let justShownVolume = false

function toggleChangingVolume(){
    const volumeBarContainer = document.getElementById('volumeBarContainer')//('volumeBar')
    if(volumeBarContainer.style.display == "none"){
        volumeBarContainer.style.display = "flex"
        justShownVolume = true
    }else{
        volumeBarContainer.style.display = "none"
    }
    // render the bar for changing volume
}

function hideChangingVolume(e){
    if(e.target.id == "volumeBar") return
    if(justShownVolume){
        justShownVolume = false
        return
    }
    document.getElementById('volumeBarContainer').style.display = "none"
}

const volumeBar = document.getElementById('volumeBar')
volumeBar.addEventListener('click', changeVolume)


// change volume of video which is between 0 and 1
function changeVolume(e){
    const volumeBarLocation = volumeBar.getBoundingClientRect()
    let newVolume = (volumeBarLocation.bottom - e.clientY)/(volumeBarLocation.bottom-volumeBarLocation.top)
    // round new volume to 2 decimals
    newVolume = Math.round(newVolume*100)/100
    video.volume = newVolume
    adjustVolumeLevelSign(volumeBarLocation.bottom - e.clientY, volumeBarLocation.bottom-volumeBarLocation.top)
}

// move sign that indicates current volume to user
function adjustVolumeLevelSign(position, volumeBarHeight){
    // position from bottom of volumeBarContainer
    document.getElementById("volumeLevelSign").style.bottom = position-volumeBarHeight+"px"
}
