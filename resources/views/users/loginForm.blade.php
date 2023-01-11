<x-layout>
<title>Login</title>
</head>
<body>
<form class="form" action="{{BASEURL}}/users/login" method="POST">   <!-- login form -->
    @csrf
    <div id="form_email">
        <input type="text" id="email" name="email" placeholder="Username or email">
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
    </div>
    <div class="form_password">
        <input type="password" id="password" name="password" placeholder="Your password">
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
    </div>
    <input class="submit" type="submit" value="Login">
</form>
</x-layout>
