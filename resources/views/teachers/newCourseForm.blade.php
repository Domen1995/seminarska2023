<x-layout>
    <title>New course</title>
</head>
<body>
    <x-teacherMenu />
    <div class="flexboxCenterer">
        <form action="{{BASEURL}}/teachers/makeNewCourse" class="form" style="justify-content:start; align-items:center">
            <label for="courseName">Name of the subject (course) as students know it: </label>
            <input type="text" id="courseName" style="height: 2rem; font-size:1.8rem">
            {{--<div style="font-size:2rem; font-family:inherit">{{$user->name}}</div>
            <a class="link" href="{{BASEURL}}/teachers/makeNewCourse" style="font-size: 3rem; font-family:inherit">Create new course</a>--}}
            {{--<label for="courseDescription" style="font-size: 2rem;"> </label>
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
            {{--<textarea id="channelDescription" name="channelDescription" rows="4" cols="60">{{$user->description}}</textarea>
            <input type="submit" value="Save?" class="submitUserdata">--}}
        </form>
    </div>
</x-layout>
