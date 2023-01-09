<x-layout>
    <title>Streaming service</title>
</head>
<body>
@foreach ($videos as $video)
    <h2 class="{{BASEURL}}videoTitle">{{$video->title}}</h2>
        <a href="{{BASEURL}}/videos/watch/{{$video->id}}">Play video!</a>
        <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">{{$video->author}}</a>
@endforeach

</x-layout>
