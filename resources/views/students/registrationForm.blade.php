<x-layout>
    <title>Create account</title>
    {{--<script src="{{BASEURL}}/js/app.js" defer></script>--}}
</head>
<x-menu />
<body>
    <form id="registration_form" class="form" action="{{BASEURL}}/users/register/student" method="POST">   <!-- form for creating account -->
        @csrf
        {{--<div id="form_email">--}}
            <input type="text" id="email" name="email" placeholder="Student's email address" value="{{old('email')}}">
            @error('email')
            <p class="error">{{$message}}</p>
            @enderror
        {{--</div>--}}
        {{--<div class="form_username">--}}
            {{--<label for="username" class="formText">Who would you like to be known as?</label>--}}
            {{-- user chooses his username --}}
            <input type="text" id="name" name="name" placeholder="Name and surname" value="{{old('name')}}">
            @error('name')
                <p class="error">{{$message}}</p>
            {{--@else
                <label for="name" class="formText">Minimum 5 characters</label>--}}
            @enderror
        {{--</div>
        <div class="form_password">--}}
            <input type="password" id="password" name="password" placeholder="Think of some password"{{--Come up with some password"--}}>
            {{--<input type="password" id="password_repeat" name="passwordRepeat" placeholder="Repeat this password"> --}}
            @error('password')
                <p class="error">{{$message}}</p>
            {{--@else
                <label for="password" class="formText">Minimum 7 characters</label>--}}
            @enderror
        {{--</div>
        <div>--}}
            <input onclick="addLoadingGif()" class="submitUserdata" type="submit" value="Create!">
        {{--</div>--}}
    </form>

    <iframe src="https://giphy.com/embed/17mNCcKU1mJlrbXodo" id="loadingGif" width="480" height="480" frameBorder="0" class="giphy-embed" style="pointer-events: none; display:none; position:fixed; top:15%; left:35%; border-radius:15rem" allowFullScreen></iframe>

    <script>
    function addLoadingGif(){
        document.getElementById("loadingGif").style.display = "block";
    }
    </script>
</x-layout>
