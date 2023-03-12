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
            <th></th>
            <th>Change number of absences (reduce = -):</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{$student->name}}</td>
                <td>{{$student->email}}</td>
                <td><a href="#">Temporarely exclude from IP testing</a></td>
                <td>
                    <form action="/teachers/reduceAbsences?studId={{$student->id}}">
                        <input id="absences" type="number" min="-100" max="100" value="0" style="width: 2.2rem">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</x-layout>
