{{-- form for uploading user's video --}}

<x-layout>
<x-menu />
    <title>Video upload</title>
    <script src="{{BASEURL}}/js/fetchAssets.js" defer></script>
</head>
<body>
    <form id="uploadForm" class="form" {{--action="{{BASEURL}}/users/store" method="POST"--}} enctype="multipart/form-data" style="height: 40rem; width:50rem; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">
    @csrf
        {{--<label for="title"></label>--}}
        <input type="text" id="title" name="title" placeholder="Video title">
        @error('title')
            <div class="error">Title required</div>
        @enderror
        <textarea name="description" id="" cols="60" rows="10" placeholder="Video description" style="width:80%; margin:0 auto"></textarea>
        {{--<input type="text" id="description" name="description" placeholder="Video description">--}}
        @error('description')
            <p class="error">{{$message}}</p>
        @enderror
        {{--<div class="genres" style="display: flex; flex-direction:column">--}}
                    {{-- genres of video: --}}
        <label for="genre" style="margin-left:auto; margin-right:auto">Your video would fall into a category of: </label>
        <div class="flexboxCenterer">
            <div class="genres" style="display: flex; flex-direction:row; gap:3rem">
                <div style="display: flex; align-items:baseline; gap:.2rem">
                    <input type="checkbox" id="music" name="genres[]" value="music" style="display: inline-block">
                    <label for="music" style="display: inline-block">Music</label>
                </div>
                {{--<input type="checkbox" id="entertainment" name="entertainment">--}}
                <div style="display: flex; align-items:baseline; gap:.2rem">
                    <input type="checkbox" id="entertainment" name="genres[]" value="entertainment" style="display: inline-block">
                    <label for="entertainment" style="display: inline-block">Entertainment</label>
                </div>
                <div style="display: flex; align-items:baseline; gap:.2rem">
                    <input type="checkbox" id="education" name="genres[]" value="education" style="display: inline-block">
                    <label for="education" style="display: inline-block">Education</label>
                </div>
            </div>
        </div>
        {{-- NE KOMENTARJA user includes a video: --}}
        <label for="videoFile" style="margin:0 auto">Your video:</label>
        {{-- a box to input video: --}}
        <div id="videoContainer" style="border:1px dashed grey; width:17rem; height:10rem; margin-left:auto; margin-right:auto">
            <input type="file" id="videoFile" name="videoFile" style="opacity: 0; width:17rem; height:10rem" {{--value="Browse ..."--}}>
        </div>
            @error('videoFile')
                <p class="error">{{$message}}</p>
            @enderror
        {{-- user can select an image that represents his video --}}
        <label for="videoImage">An image that will represent your video; optional: </label>
        <input type="file" name="videoImage" id="videoImage" value="Browse images ...">
        {{-- form submission --}}
        <input type="submit" value="Upload!" onclick="addLoadingGif()" style="width:13rem; height:5rem" draggable="true">
    </form>
    {{-- gif triggered by submit, symbolizing loading --}}
    <iframe src="https://giphy.com/embed/17mNCcKU1mJlrbXodo" id="loadingGif" width="480" height="480" frameBorder="0" class="giphy-embed" style="pointer-events: none; display:none; position:fixed; top:15%; left:35%; border-radius:15rem" allowFullScreen></iframe>
</x-layout>
