<x-layout>
    <title>Confirming presence</title>
    <script src="{{BASEURL}}/js/confirmWindow.js"></script>
</head>
<body>
    <div class="flexboxCenterer">
        <div class="form" style="width: 50%">
            <div style="color:red">Select the course only if you are at the lecture at this moment:
            </div>
            @foreach ($courses as $course)
                <a href="/students/selected_course_for_checking/{{$course->id}}">{{$course->name}}</a>
            @endforeach
            {{--
            <div id="ipQuestion" style="display:flex; gap:2rem; position:relative">
                <button class="link" onclick="showIpConfirmation('{{$studentsIP}}')" style="height: .9rem">One of my 2 IP address will be {{$studentsIP}} forever</button>
                <form action="{{BASEURL}}/users/logout" method="POST" style="">
                    @csrf
                    <input class="link" type="submit" value="Log out" style="width:14rem; height:6rem; font-size:4rem">
                </form>
            </div>
            --}}
        </div>
    </div>
</x-layout>
