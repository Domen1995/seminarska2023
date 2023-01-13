<div class="videoGrid">
    @foreach ($videos as $video)
    <div class="videoContainer">
        <a class="video" href="{{BASEURL}}/videos/watch/{{$video->id}}">
            <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
            <img src="" alt="image missing">
        </a>
        <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">By {{$video->author}}</a>
    </div>
    @endforeach
</div>
