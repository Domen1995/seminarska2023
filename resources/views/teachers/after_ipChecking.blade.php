<x-layout>
    <title>Present stored</title>
    <link rel="stylesheet" href="/css/tables.css">
    <script src="/js/confirmWindow.js"></script>
</head>
<body>
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

    <div id="revertQuestion">Now it's the last opportunity to revert the whole testing.
        <button onclick="show_revert_ip_checking_confirmation
            @if ($presentStudentIds==null) {{-- if no students have connected --}}
                (-1)
            @else
                ({{implode(',', $presentStudentIds)}})
            @endif
            ">Revert?
        </button>
    </div>
</x-layout>
