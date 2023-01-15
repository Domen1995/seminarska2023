<div class="videoGrid">
    @foreach ($videos as $video)
    <div class="videoContainer">
        <a class="video" href="{{BASEURL}}/videos/watch/{{$video->id}}">
            <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
            <img class="videoImage" src="{{--$video->videoImagePath--}}{{BASEURL}}/storage/{{$video->videoImagePath}}" alt="Play video!">
        </a>
        <br>
        <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">By {{$video->author}}</a>
        <div>{{$video->views}} views</div>
    </div>
    @endforeach
</div>
{{$videos->links()}}
