<x-layout>
<title>Login</title>
<script src="{{BASEURL}}/js/app.js" defer></script>
</head>
<body>
    {{-- display menu for register, login, logout ... --}}
<x-menu />
<form class="form" action="{{BASEURL}}/users/login" method="POST">   <!-- login form -->
    @csrf
    {{--<div id="form_email">--}}
        <input type="text" id="email" name="email" placeholder="Username or email" value="{{old('email')}}">
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
    {{--</div>--
    <div class="form_password">--}}
        <input type="password" id="password" name="password" placeholder="Your password" value="{{old('password')}}">
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
    {{--</div>
    <div>--}}
        <input class="submitUserdata" type="submit" value="Sign in">
    {{--</div>--}}
</form>
</x-layout>
