const baseurl = "https://localhost/seminarska2023/public"

function addLoadingGif(){
    //let gif = await fetch("../assets/gif/loading");
    //if()
    document.getElementById("loadingGif").style.display = "block";
    //gifElement.style.display = "block";
    //gifElement.src="https://giphy.com/embed/17mNCcKU1mJlrbXodo";
    /*gifElement.width = 480;
    gifElement.height = 480;*/
}

const videoContainer = document.getElementById('videoContainer')

videoContainer.addEventListener("dragover", (e)=>{
    e.preventDefault()
    videoContainer.style.backgroundColor = "lightgreen"
})

videoContainer.addEventListener("dragleave", (e)=>{
    videoContainer.style.backgroundColor = "rgb(240, 240, 240)"
})

//let video
videoContainer.addEventListener("drop", (e)=>{
    e.preventDefault()
    //video = e.dataTransfer.files
    document.getElementById('videoFile').files = e.dataTransfer.files
    /*const formVideo = new FormData
    formVideo.append("videoFile", video)
    document.getElementById('videoFile').value = video
    console.log(document.getElementById('videoFile').value)*/
    //document.getElementById("videoFile").value = formVideo
    //console.log(document.getElementById("videoFile").value.size)
})

imageContainer.addEventListener("dragover", (e)=>{
    e.preventDefault()
    imageContainer.style.backgroundColor = "lightgreen"
})

imageContainer.addEventListener("dragleave", (e)=>{
    imageContainer.style.backgroundColor = "rgb(240, 240, 240)"
})

let image
videoContainer.addEventListener("drop", (e)=>{
    e.preventDefault()
    //video = e.dataTransfer.files
    document.getElementById('videoImage').files = e.dataTransfer.files
    /*const formVideo = new FormData
    formVideo.append("videoFile", video)
    document.getElementById('videoFile').value = video
    console.log(document.getElementById('videoFile').value)*/
    //document.getElementById("videoFile").value = formVideo
    //console.log(document.getElementById("videoFile").value.size)
})
/*
const uploadForm = document.getElementById("uploadForm")
const usersToken = document.getElementById('usersToken').content

// send video form to server
uploadForm.addEventListener("submit", (e)=>{
    e.preventDefault()
    const videoData = new FormData(uploadForm)
    videoData.append("videoFile", video)
    console.log(video)
    fetch(baseurl+"/users/store", {
        method: "POST",
        credentials: "same-origin",
        body: videoData
    })
    .then(resp =>{
        console.log(resp.text)
    })
    //.then(location = baseurl)
})
*/
