{{-- NOT IN USE --}}
<x-layout>
    <title>My statistics</title>
    <link rel="stylesheet" href="/css/tables.css">
</head>
<body>
    <x-studentMenu />
    @if(count($courses_infos)==0)
        <h2>You're not enrolled in any course yet.</h2>
    @else
        @include('students.statistics_table')
    @endif
</x-layout>
