<link rel="stylesheet" href="{{BASEURL}}/css/videoGrid.css">
<div class="videoGrid">
    @foreach ($videos as $video)
    <div class="videoContainer">
        @auth
            @if($video->user_id == auth()->user()->id)
                <a href="deleteVideo">Delete?</a>
            @endif
        @endauth
        <a class="video" href="{{BASEURL}}/videos/watch/{{$video->id}}">
            <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
            <img class="videoImage" src="{{--$video->videoImagePath--}}{{BASEURL}}/storage/{{$video->videoImagePath}}" alt="Play video!">
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
