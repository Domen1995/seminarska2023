<table class="students">
    <tr>
        <th>Subject</th>
        <th>Screw-ups</th>
        <th>Presences</th>
    </tr>
    @foreach ($courses_infos as $course_infos)
        <tr>
            <td>{{$course_infos->name}}</td>
            <td>{{$course_infos->screwUps}}</td>
            <td>{{$course_infos->presences}}</td>
        </tr>
    @endforeach
</table>
