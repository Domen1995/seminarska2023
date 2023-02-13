<x-layout>
    <title>{{$course->name}}</title>
    {{--<link rel="stylesheet" href="css/videoGrid.css">--}}
</head>
<body>
{{-- display menu for register, login, logout ... --}}

@if ($user->isTeacher)
    <x-teacherMenu />

@else
    <x-studentMenu />
@endif

@if ($course->user_id == auth()->user()->id)
    @include('teachers.courseManager')
@endif

{{--
@auth
    <p>Logged in, {{$user->name}}!</p>
@endauth
--}}

{{--@include('videos.searchForm')--}}
@include('videos.videoGrid')
{{--
@foreach ($videos as $video)
    <h2 class="{{BASEURL}}/videoTitle">{{$video->title}}</h2>
        <a href="{{BASEURL}}/videos/watch/{{$video->id}}">Play video!</a>
        <a href="{{BASEURL}}/users/profile/{{$video->user_id}}">{{$video->author}}</a>
@endforeach
--}}

</x-layout>
