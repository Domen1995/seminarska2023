<x-layout>
    <title>Present stored</title>
    <link rel="stylesheet" href="/css/tables.css">
    <script src="/js/confirmWindow.js"></script>
</head>
<x-teacherMenu />
<table class="students">
    @foreach ($studentsInCourse as $student)
        <tr>
            <td>{{$student->name}}</td>
            @if($presentStudentIds!=null && in_array($student->id, $presentStudentIds))
                <td style="background-color:green">Presences + 1</td>
            @else
                <td style="background-color:red">Screw ups + 1</td>
            @endif
        </tr>
    @endforeach
</table>
<div>Now it's the last opportunity to revert the whole testing.
    <button onclick="show_revert_ip_checking_confirmation({{implode('', $presentStudentIds)}})">Revert?</button>
</div>
</x-layout>
