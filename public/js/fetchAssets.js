//const { read } = require("@popperjs/core");

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

const imageContainer = document.getElementById('imageContainer')

imageContainer.addEventListener("dragover", (e)=>{
    e.preventDefault()
    imageContainer.style.backgroundColor = "lightgreen"
})

imageContainer.addEventListener("dragleave", (e)=>{
    imageContainer.style.backgroundColor = "rgb(240, 240, 240)"
})

//let image
imageContainer.addEventListener("drop", (e)=>{
    e.preventDefault()
    //video = e.dataTransfer.files
    const transferredImage = e.dataTransfer.files
    document.getElementById('videoImage').files = transferredImage//e.dataTransfer.files
    // set this image as background if image container
    //console.log(transferredImage[0])
    const reader = new FileReader()
    //reader.readAsArrayBuffer(transferredImage[0])
    reader.readAsDataURL(transferredImage[0])
    reader.onload = function(){
        const readImage = reader.result
        console.log(readImage)
        document.getElementById('imageContainer').style.background = "url("+readImage+")"//"data:image/jpg;base64("+readImage+")"
    }
    return
    const readImage = read.readAsDataUrl(transferredImage[0])
    console.log(32)
    //document.body.style.backgroundImage = transferredImage[0]
    document.getElementById('imageContainer').style.backgroundImage = readImage//transferredImage[0]//"url(https://flxt.tmsimg.com/assets/74353_v9_bb.jpg)"//transferredImage[0]
    console.log(transferredImage[0])
    console.log(document.getElementById('imageContainer').style.backgroundImage)
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
