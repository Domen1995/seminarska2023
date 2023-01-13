<div class="menu">
    {{-- if not logged in, display options for guest, ouherwise for a logged in user --}}
    @guest
        <a class="link" href="{{BASEURL}}/users/loginForm" style="">Login</a>
        <a class="link" href="{{BASEURL}}/users/registrationForm">Register</a>
        <a class="link" href="{{BASEURL}}/"><i class="material-icons" style="">home</i>Home</a>
    @else
    <form action="{{BASEURL}}/users/logout" method="POST" style="margin-left:auto">
        @csrf
        <input class="link" type="submit" value="Sign out" style="">
    </form>
    {{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
    @endguest
</div>
