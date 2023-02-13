<x-layout>
<title>Sign in</title>
{{--<script src="{{BASEURL}}/js/app.js" defer></script>--}}
</head>
<body>
    {{-- display menu for register, login, logout ... --}}
<x-menu />
<form class="form" action="{{BASEURL}}/users/login" method="POST">   <!-- login form -->
    @csrf
    {{--<div id="form_email">--}}
        <div class="formMainText">Already have an account? Sign in:</div>
        <input type="text" id="email" name="email" placeholder="Username or email" value="{{old('email')}}">
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
    {{--</div>--
    <div class="form_password">--}}
        <input type="password" id="password" name="password" placeholder="Password" value="{{old('password')}}">
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
    {{--</div>
    <div>--}}
        <input class="submitUserdata" type="submit" value="Sign in">
    {{--</div>--}}
</form>
</x-layout>
