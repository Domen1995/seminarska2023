<div class="menu">
    {{-- if not logged in, display options for guest, ouherwise for a logged in user --}}
    @guest
        <a class="link" href="{{BASEURL}}/users/loginForm" style="">Login</a>
        <a class="link" href="{{BASEURL}}/users/registrationForm">Register</a>
    @else
    <form action="{{BASEURL}}/users/logout" method="POST" style="margin-left:auto">
        @csrf
        <input class="link" type="submit" value="Sign out" style="">
    </form>
    {{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
    @endguest
</div>

@auth
    <h2>Logged in. Welcome,
    </h2>
@else
    <h2>Not logged in</h2>
@endauth
