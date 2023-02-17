<table class="courseList">
    <caption style="font-size:1.2rem; width:12rem">Requesting students:</caption>
    @foreach ($studentsToEnroll as $student)
        <tr>
            <td>{{$student->name}}</td>
            <td>{{$student->email}}</td>
            <td><a href="{{BASEURL}}/teachers/enrollStudent?studentID={{$student->id}}&courseID={{$course->id}}">Let him in</a></td>
        </tr>
    @endforeach
</table>
