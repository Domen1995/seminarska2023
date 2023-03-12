{{-- students enrolled in a particular course --}}
<x-layout>
    <title>Manage students</title>
    <link rel="stylesheet" href="/css/tables.css">
</head>
<body>
<x-teacherMenu />
    <table class="students">
        <caption>The settings only apply to this course</caption>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th></th>
            <th>Change number of absences</th>
        </tr>
        @foreach ($students_info as $student_info)
            <tr>
                <td>{{$student_info->name}}</td>
                <td>{{$student_info->email}}</td>
                <td>{{$student_info->status}}</td>
                @if($student_info->status == "enrolled")
                    <td><a href="/teachers/allow_without_testings/{{$course->id}}/{{$student_info->id}}" title="Also temporarely excludes him from IP testing" style="color: black">Allow watching without visiting lectures</a></td>
                @else
                    <td><a href="/teachers/not_allow_without_testings/{{$course->id}}/{{$student_info->id}}" title="Also temporarely excludes him from IP testing" style="color: black">Make him visit lectures</a></td>
                @endif
                <td>
                    <form action="/teachers/reduceAbsences?studId={{$student_info->id}}">
                        <label for="absences">Reduce = -1</label>
                        <input id="absences" type="number" min="-100" max="100" value="0" style="width: 2.2rem">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</x-layout>
