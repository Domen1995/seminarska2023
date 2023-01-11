@auth
    <h2>Logged in. Welcome,
    </h2>
@else
    <h2>Not logged in</h2>
@endauth

<div class="menu">
    {{-- if not logged in, display options for guest, ouherwise for a logged in user --}}
    @guest
    <a href="{{BASEURL}}/users/loginForm">Login</a>
    <a href="{{BASEURL}}/users/registrationForm">Register</a>
    @else
    <form action="{{BASEURL}}/users/logout" method="POST">
        @csrf
        <input type="submit" value="Sign out">
    </form>
    {{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
    @endguest
</div>
