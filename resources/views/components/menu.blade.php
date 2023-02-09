<div class="menu">
    {{-- if not logged in, display options for guest, ouherwise for a logged in user --}}
    @guest
        <div id="passwordMenuColumn" class="dropdownColumn">
            <button id="sign_in" class="link">Sign in</button>
            <div id="dropdownItemsContainer" class="dropdownItemsContainer">
                <a class="link" href="{{BASEURL}}/users/loginForm" style="">As student</a>
                <a class="link" href="{{BASEURL}}/users/loginForm" style="">As teacher</a>
            </div>
        </div>
        <a class="link" href="{{BASEURL}}/users/registrationForm">Register as teacher</a>
        <a class="link" href="{{BASEURL}}/"><i class="material-icons" style="">home</i>Home</a>
    @else
        {{--<div>Welcome, {{auth()->id()}}!</div>--}}
        {{--<div class="dropdown">--}}
            <a href="{{BASEURL}}/users/selfProfile{{--auth()->id()--}}" class="link">{{auth()->user()->name}}'s profile</a>
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
