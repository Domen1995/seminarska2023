{{-- form for uploading user's video --}}

<x-layout>
    <title>Video upload</title>
</head>
<body>
    <form action="/users/store" method="POST" enctype="multipart/form-data">
    @csrf
        <label for="title"></label>
        <input type="text" id="title" name="title" placeholder="Video title">
        @error('title')
            <div class="error">Title required</div>
        @enderror
        <input type="text" id="description" name="description" placeholder="Video description">
        <label for="genre">Your video would fall into a category of: </label>
        {{-- genres of video: --}}
        <input type="checkbox" id="music" name="music">
        <label for="music">Music</label>
        <input type="checkbox" id="entertainment" name="entertainment">
        <label for="entertainment">Entertainment</label>
        <input type="checkbox" id="education" name="education">
        <label for="education">Education</label>
        {{-- user includes a video: --}}
        <input type="file" id="videoFile" name="videoFile">

        {{-- form submission --}}
        <input type="submit" value="Upload!">
    </form>
</x-layout>
