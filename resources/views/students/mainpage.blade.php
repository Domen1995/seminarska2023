<x-layout>
    <title>My courses</title>
</head>
<body>
    <x-studentMenu />
    @include('courses.search')
    {{--
    {{session()->getId()}}
    {{session("ses")}}
    {{(session(["bla" => "blahblah"]))}}
    @if((session()->has("bla")))
        <p>{{session("bla")}}</p>
    @endif--}}
    @include('courses.courseList')
    {{--
    @if(count($courses)>0)
        @foreach ($courses as $course)
            <div>{{$course->name}}</div>
        @endforeach
    @endif
    --}}
</x-layout>
