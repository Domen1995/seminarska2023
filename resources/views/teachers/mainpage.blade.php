<x-layout>
    <title>My courses</title>
</head>
<body>
    <x-teacherMenu />
    @include('courses.search')
    <div class="flexboxCenterer">
        <div class="form" style="justify-content:start; align-items:center">
            <div style="font-size:2rem; font-family:inherit">{{$user->name}}</div>
            <a class="link" href="{{BASEURL}}/teachers/newCourseForm" style="font-size: 3rem; font-family:inherit">Create new course</a>
            {{--<label for="channelDescription" style="font-size: 2rem;">Describe your channel: </label>--}}
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
            {{--<textarea id="channelDescription" name="channelDescription" rows="4" cols="60">{{$user->description}}</textarea>
            <input type="submit" value="Save?" class="submitUserdata">--}}
        </div>
    </div>
    @if(count($courses)>0)
        @foreach ($courses as $course)
            <div>{{$course->name}}</div>
        @endforeach
        {{--
        <div style="margin-left: auto; margin-right:auto; width:15rem; text-align:center; background-color:#4b3268;font-size:2rem; border-radius:.3rem">Your creations: </div>
        @include('videos.videoGrid')--}}
    @endif
</x-layout>
