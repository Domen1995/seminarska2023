<x-layout>
    <title>Upload your video</title>
</head>
<body>
    <form action="/videos/store" method="POST"></form>
    @csrf
        <label for="title"></label>
        <input type="text" id="title" name="title" placeholder="Name your video">
        <input type="file" id="videoFile">
        <input type="submit" value="Upload!">
</x-layout>
