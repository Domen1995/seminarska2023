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

videoContainer.addEventListener("drop", (e)=>{
    e.preventDefault()
    let video = e.dataTransfer.files
    document.getElementById("videoFile").value = video
})
