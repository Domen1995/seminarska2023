<x-layout>
    <title></title>
    <link rel="stylesheet" href="{{BASEURL}}/css/videoPlayer.css">
    <script src="{{BASEURL}}/js/videoPlayer.js" defer></script>
</head>
<body>
    <x-menu />
    <div class="videoHeader">
        <div class="selectedVideoTitle">{{$video->title}}</div>
        <a class="arrowBack" href="{{BASEURL}}/"><i style="font-size: 2.5rem" class="material-icons">arrow_back</i></a>
    </div>
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
                <div class="timelineContainer">
                    <div id="timeline" class="timeline">{{--<i id="timelineButton" class="material-icons">circle</i>--}}</div>
                    <div id="timelineButton"></div>{{--<i id="timelineButton" class="material-icons">circle</i>--}}
                </div>
                <div id="totalTime">10:00</div>
            </div>
            <div id="volumeSettings" class="volumeSettings">
                <div class="volumeIcon link" id="volumeIcon">
                    <i class="material-icons" style="font-size: 2rem; user-select:none">volume_up</i>
                </div>
                <div id="volumeBarContainer" class="volumeBarContainer">
                    <div id="volumeBar" class="volumeBar"></div>
                    <i id="volumeCircle" class="material-icons">circle</i>
                </div>
            </div>
            <div onclick="toggleFullscreen()" class="controls" id="fullscreen">
                <button {{--onclick="toggleFullscreen()"--}} class="link"><i class="material-icons" id="fullscreenIcon">fullscreen</i></button>
            </div>
        </div>
        <video onclick="togglePlay()" ondblclick="toggleFullscreen()" src="{{BASEURL}}/videos/chunk/{{$video->id}}"class="selectedVideo" id="video" width="100%" autoplay {{--style="position:absolute; {{--z-index:-1"--}}></video>
    </div>
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
