{{-- form for uploading user's video --}}

<x-layout>
<x-menu />
    <title>Video upload</title>
    <script src="{{BASEURL}}/js/fetchAssets.js"></script>
</head>
<body>
    <form action="{{BASEURL}}/users/store" method="POST" enctype="multipart/form-data">
    @csrf
        {{--<label for="title"></label>--}}
        <input type="text" id="title" name="title" placeholder="Video title">
            @error('title')
                <div class="error">Title required</div>
            @enderror
        <input type="text" id="description" name="description" placeholder="Video description">
            @error('description')
                <p class="error">{{$message}}</p>
            @enderror
        <label for="genre">Your video would fall into a category of: </label>
        {{-- genres of video: --}}
        <input type="checkbox" id="music" name="genres[]" value="music">
        <label for="music">Music</label>
        {{--<input type="checkbox" id="entertainment" name="entertainment">--}}
        <input type="checkbox" id="entertainment" name="genres[]" value="entertainment">
        <label for="entertainment">Entertainment</label>
        <input type="checkbox" id="education" name="genres[]" value="education">
        <label for="education">Education</label>
        {{-- user includes a video: --}}
        <label for="videoFile">Your video:
        <input type="file" id="videoFile" name="videoFile" value="Browse ...">
            @error('videoFile')
                <p class="error">{{$message}}</p>
            @enderror
        {{-- user can select an image that represents his video --}}
        <label for="videoImage">An image that will represent your video; optional: </label>
        <input type="file" name="videoImage" id="videoImage" value="Browse images ...">
        {{-- form submission --}}
        <input type="submit" value="Upload!" onclick="addLoadingGif()">
    </form>
    {{-- gif triggered by submit, symbolizing loading --}}
    <iframe src="https://giphy.com/embed/17mNCcKU1mJlrbXodo" id="loadingGif" width="480" height="480" frameBorder="0" class="giphy-embed" style="pointer-events: none; display:none" allowFullScreen></iframe>
</x-layout>
