{{-- students enrolled in a particular course --}}
<x-layout>
    <title>Manage students</title>
    <link rel="stylesheet" href="/css/tables.css">
</head>
<body>
<x-teacherMenu />
    @if(count($students_info) == 0)
        <h2 style="text-align:center; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">There's no student enrolled in this course</h2>
    @else
    <table class="students">
        <caption>These settings only apply to {{$course->name}}</caption>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th></th>
            <th>Screw-ups</th>
            <th>Presences</th>
            <th>Change number of absences</th>
            <th>Ban from course</th>
        </tr>
        @foreach ($students_info as $student_info)
            <tr>
                <td>{{$student_info->name}}</td>
                <td>{{$student_info->email}}</td>
                <td>
                    @switch($student_info->status)
                        @case("enrolled") Enrolled
                            @break
                        @case("allowed_missing") Allowed to miss
                            @break
                        @case("banned") Banned
                    @endswitch
                </td>
                @if($student_info->status == "enrolled")
                    <td><a href="/teachers/allow_without_testings/{{$course->id}}/{{$student_info->id}}" title="Also temporarely excludes him from IP testing">Allow watching without visiting lectures</a></td>
                @else
                    <td><a href="/teachers/not_allow_without_testings/{{$course->id}}/{{$student_info->id}}">Make him visit lectures</a></td>
                @endif
                <td>{{$student_info->screwUps}}</td>
                <td>{{$student_info->presences}}</td>
                <td>
                    <form action="/teachers/change_absences/{{$course->id}}/{{$student_info->id}}">
                        <label for="absences">Decrement = -1</label>
                        <input id="absences" name="absences" type="number" min="-100" max="100" value="-1" style="width: 2.2rem">
                        {{--<input type="hidden" name="student_id" value="{{$student_info->id}}">
                        <input type="hidden" name="course_id" value="{{$course->id}}">--}}
                        <button type="submit" style="height:1.5rem; transform:translateY(.5rem); background-color:green"><i class="material-icons">done</i></button>
                    </form>
                </td>
                @switch($student_info->status)
                    @case("banned")
                        <td><a href="/teachers/unban_from_course/{{$course->id}}/{{$student_info->user_id}}">Bring him back</a></td>
                        @break
                    @default
                    <td><a href="/teachers/ban_from_course/{{$course->id}}/{{$student_info->user_id}}">Ban until revoke</a></td>
                @endswitch
            </tr>
        @endforeach
    </table>
    @endif
</x-layout>
