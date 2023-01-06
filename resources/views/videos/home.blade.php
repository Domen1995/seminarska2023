<x-layout>
    <title>Streaming service</title>
</head>
<body>
@foreach ($videos as $video)
    <h2 class="videoTitle">{{$video->title}}</h2>
        <a href="/videos/{{$video->id}}/play">Play video!</a>
        <a href="/profile/{{$video->user_id}}">{{$video->author}}</a>
@endforeach

</x-layout>
