<x-layout>
    <title>Create account</title>
    <script src="{{BASEURL}}/js/app.js" defer></script>
</head>
<body>
    <form id="registration_form" class="form" action="{{BASEURL}}/users/register" method="POST">   <!-- form for creating account -->
        @csrf
        <div id="form_email">
            <input type="text" id="email" name="email" placeholder="Your email address" value="{{old('email')}}">
            @error('email')
            <p class="error">{{$message}}</p>
            @enderror
        </div>
        <div class="form_username">
            {{--<label for="username" class="formText">Who would you like to be known as?</label>--}}
            {{-- user chooses his username --}}
            <input type="text" id="name" name="name" placeholder="Who would you like to be known as?" value="{{old('name')}}">
            @error('name')
                <p class="error">{{$message}}</p>
            {{--@else
                <label for="name" class="formText">Minimum 5 characters</label>--}}
            @enderror
        </div>
        <div class="form_password">
            <input type="password" id="password" name="password" placeholder="Come up with some password">
            {{--<input type="password" id="password_repeat" name="passwordRepeat" placeholder="Repeat this password"> --}}
            @error('password')
                <p class="error">{{$message}}</p>
            {{--@else
                <label for="password" class="formText">Minimum 7 characters</label>--}}
            @enderror
        </div>
        <div>
            <input class="submit" type="submit" value="Create an account!">
        </div>
    </form>
</x-layout>
