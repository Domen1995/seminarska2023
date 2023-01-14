<div class="menu">
    {{-- if not logged in, display options for guest, ouherwise for a logged in user --}}
    @guest
        <a class="link" href="{{BASEURL}}/users/loginForm" style="">Login</a>
        <a class="link" href="{{BASEURL}}/users/registrationForm">Register</a>
        <a class="link" href="{{BASEURL}}/"><i class="material-icons" style="">home</i>Home</a>
    @else
        {{--<div>Welcome, {{auth()->id()}}!</div>--}}
        {{--<div class="dropdown">--}}
            <a href="{{BASEURL}}/users/selfProfile{{--auth()->id()--}}" class="link">My profile</a>
            {{--<div class="dropdown-menu">
                <a href="{{BASEURL}}/users/uploadForm" class="link">Upload your video</a>
            </div>--}}
        {{--</div>--}}
        <a class="link" href="{{BASEURL}}/"><i class="material-icons" style="">home</i>Home</a>
    <form action="{{BASEURL}}/users/logout" method="POST" style="margin-left:auto">
        @csrf
        <input class="link" type="submit" value="Sign out" style="">
    </form>
    {{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
    @endguest
</div>
