<x-layout>
    <title>Streaming service</title>
    <link rel="stylesheet" href="css/videoGrid.css">
</head>
<body>
{{-- display menu for register, login, logout ... --}}
<x-menu />
@auth
    <p>Welcome, {{$user->name}}!</p>
@endauth

@include('videos.videoGrid')
{{--
@foreach ($videos as $video)
    <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
        <a href="{{BASEURL}}/videos/watch/{{$video->id}}">Play video!</a>
        <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">{{$video->author}}</a>
@endforeach
--}}

</x-layout>
