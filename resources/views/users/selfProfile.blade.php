<x-layout>
    <title>My profile</title>
</head>
<body>
    <x-menu />
    <div class="flexboxCenterer">
        <form action="{{BASEURL}}/users/profile/update" method="POST" class="form" style="justify-content:start; align-items:center">
            @csrf
            @method('PUT')
            {{--<input type="text" value="{{auth()->user()->name}}" disabled>--}}
            <div style="font-size:2rem; font-family:inherit">{{$user['name']}}</div>
            <a class="link" href="{{BASEURL}}/users/uploadForm" style="font-size: 3rem; font-family:inherit">Upload new video</a>
            <label for="channelDescription" style="font-size: 2rem;">Describe your channel: </label>
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
            <textarea id="channelDescription" name="channelDescription" rows="4" cols="60">{{$user['description']}}</textarea>
            <input type="submit" value="Save?" class="submitUserdata">
        </form>
    </div>
</x-layout>
