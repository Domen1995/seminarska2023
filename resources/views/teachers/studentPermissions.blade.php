<x-layout>
    <title>New course</title>
</head>
<body>
    <x-teacherMenu />
    <div class="flexboxCenterer">
        <form action="{{BASEURL}}/courses/create" method="POST" class="form" style="justify-content:start; align-items:center">
            @csrf
            <label for="allowedEmails">Your courses will only be visible to the students whose emails end with: </label>
            <input type="text" id="allowedEmails" style="height: 2rem; font-size:1.8rem">
            @error('courseName')
                <p class="error">{{$message}}</p>
            @enderror
            <label for="faculty">Faculty acronym, doesn't need to be official: </label>
            <input type="text" id="faculty" style="height: 2rem; font-size:1.8rem">
            @error('faculty')
                <p class="error">{{$message}}</p>
            @enderror
            {{--<div style="font-size:2rem; font-family:inherit">{{$user->name}}</div>
            <a class="link" href="{{BASEURL}}/teachers/makeNewCourse" style="font-size: 3rem; font-family:inherit">Create new course</a>--}}
            {{--<label for="courseDescription" style="font-size: 2rem;"> </label>
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
            {{--<textarea id="channelDescription" name="channelDescription" rows="4" cols="60">{{$user->description}}</textarea>--}}
            <input type="submit" value="Save" class="submitUserdata" style="width: 20rem">
        </form>
    </div>
</x-layout>
