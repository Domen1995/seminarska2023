<x-layout>
    <title></title>
</head>
<body>
    <x-menu />
    <video class="videoArea" width="500" controls autoplay>
        <source src="{{BASEURL}}/videos/chunk/{{$video->id}}">
    </video>
</x-layout>
