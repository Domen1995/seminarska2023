<x-layout>
    <title></title>
    <link rel="stylesheet" href="{{BASEURL}}/css/videoPlayer.css">
    <script src="{{BASEURL}}/js/videoPlayer.js" defer></script>
</head>
<body>
    <x-menu />
    <div class="selectedVideoTitle">{{$video->title}}</div>
    <div id="videoMainContainer" class="videoMainContainer">
        {{--<video class="videoArea" id="video" width="100%"{{--"500"--} controls="false" autoplay style="position:absolute; z-index:-1">
            <source style="position: absolute" style="z-index:0" src="{{BASEURL}}/videos/chunk/{{$video->id}}">
        </video>--}}
        {{--<video src="{{BASEURL}}/videos/chunk/{{$video->id}}"class="videoArea" id="video" width="100%" controls autoplay style="position:absolute; z-index:-1"></video>--}}
        <div id="studentID" class="studentID" style="position:absolute; top:16rem; left:50%; z-index:2">Domen Kamnik</div>
        <div id="videoControlsContainer" class="videoControlsContainer">
            <div class="controls">
                <button onclick="togglePlay()" class="link"><i class="material-icons" id="playIcon" style="font-size: 3rem">play_circle</i></button>
            </div>
            <div class="durationContainer">
                <div id="currentTime">0:00</div>
                <div id="timeline" class="timeline"><i id="timelineButton" class="material-icons">circle</i></div>
                <div id="totalTime">10:00</div>
            </div>
            <div class="controls">
                <button onclick="toggleFullscreen()" class="link"><i class="material-icons" id="fullscreenIcon" style="font-size:3rem; {{--z-index:3--}}">fullscreen</i></button>
            </div>
        </div>
        <video onclick="togglePlay()" ondblclick="toggleFullscreen()" src="{{BASEURL}}/videos/chunk/{{$video->id}}"class="selectedVideo" id="video" width="100%" autoplay {{--style="position:absolute; {{--z-index:-1"--}}></video>
    </div>
    <a style="display: block" href="{{BASEURL}}/"><i class="material-icons">arrow_back</i></a>
</x-layout>
<script>document.getElementById('videoMainContainer').addEventListener('contextmenu', (e)=>{e.preventDefault()})</script>
{{--
<script>
    document.addEventListener('load', ()=>{
        document.getElementById('video').allowFullscreen = "false"
    })
    document.addEventListener("fullscreenchange", async ()=>{
        //await document.exitFullscreen()
        document.getElementById('videoContainer').requestFullscreen()
    })
</script>
--}}
