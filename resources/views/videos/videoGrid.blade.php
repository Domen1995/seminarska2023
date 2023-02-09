<link rel="stylesheet" href="{{BASEURL}}/css/videoGrid.css">
{{--<script src="{{BASEURL}}/js/app.js" defer></script>--}}
<div class="videoGrid">
    @foreach ($videos as $video)
    <div class="videoContainer" id="videoContainer{{$video->id}}" style="position: relative">
        @auth
            @if($video->user_id == auth()->user()->id)
                <button onclick="confirmDelete({{$video->id}})" {{--href="deleteVideo"--}} style="position: absolute; left:9rem; top:2rem; z-index: 1;"><i class="material-icons" style="color: red; font-size:1.5rem">delete</i></button>
            @endif
        @endauth
        <a class="video" {{--id="video"--}} href="{{BASEURL}}/videos/watch/{{$video->id}}" style="position: relative">
            <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
            <img class="videoImage" src="{{--$video->videoImagePath--}}{{BASEURL}}/storage/{{$video->videoImagePath}}" alt="Play video!">
            {{--@auth
            @if($video->user_id == auth()->user()->id)
                <button id="confirmDel" onclick="confirmDelete($video->title)" {{--href="deleteVideo" style="position: absolute; left:9rem"><i class="material-icons" style="color: red; font-size:3rem">delete</i></button>
            @endif
        @endauth--}}
        </a>
        <br>
        By <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">{{$video->author}}</a>
        <div>{{$video->views}} views</div>
    </div>
    @endforeach
</div>

<div class="flexboxCenterer">
    <div class="pageList" style="display: flex">
        {{$videos->links('pagination::bootstrap-4')}}
    </div>
</div>

<script>
</script>
