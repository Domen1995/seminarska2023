<x-layout>
    <title></title>
    <link rel="stylesheet" href="{{BASEURL}}/css/videoPlayer.css">
    {{--<script src="{{BASEURL}}/js/videoPlayer.js" defer></script>--}}
</head>
<body>
    @auth
        @if(auth()->user()->isTeacher)
            <x-teacherMenu />
        @else
            <x-studentMenu />
        @endif
    @endauth
    {{--<x-menu />--}}
    <video ondblclick="toggleFullscreen()" src="{{BASEURL}}/videos/chunk/2"class="selectedVideo" id="video" width="100%" controls autoplay {{--style="position:absolute; {{--z-index:-1"--}}></video>
</x-layout>
{{--<script>document.getElementById('videoMainContainer').addEventListener('contextmenu', (e)=>{e.preventDefault()})</script>
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
