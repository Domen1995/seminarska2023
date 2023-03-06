<x-layout>
    <title>My profile</title>
    <script src="/js/selfProfile.js"></script>
    <meta id="usersToken" content="{{ csrf_token() }}">
</head>
<body>
    <x-teacherMenu :courses/>
    <div class="flexboxCenterer">
        <div class="form" style="justify-content:start; align-items:center">
            <div style="font-size:2rem; font-family:inherit">{{$user->name}}</div>
            {{--<a class="link" href="{{BASEURL}}/teachers/newCourse" style="font-size: 3rem; font-family:inherit">Create new course</a>--}}
            <label for="info_for_students" style="font-size: 2rem;">Some informations for your students, if needed:</label>
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
            <textarea id="info_for_students" name="info_for_students" rows="4" cols="60">{{$info_for_students}}</textarea>
            {{--<input type="submit" value="Save?" class="submitUserdata">--}}
            <button onclick="update_info_for_students()" class="submitUserdata" style="font-size: 2rem">Save?</button>
        </div>
    </div>
    {{--
    @if(count($courses)>0)
        <div style="margin-left: auto; margin-right:auto; width:15rem; text-align:center; background-color:#4b3268;font-size:2rem; border-radius:.3rem">Your creations: </div>
        @include('videos.videoGrid')
    @endif--}}
</x-layout>
