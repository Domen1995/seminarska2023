<div class="menu">
            {{--<div class="dropdown-menu">
                <a href="{{BASEURL}}/users/uploadForm" class="link">Upload your video</a>
            </div>--}}
        {{--</div>--}}
    <a href="{{BASEURL}}/students/statistics{{--auth()->id()--}}" class="link">{{auth()->user()->name}}'s statistics</a>
    <a href="{{BASEURL}}/students/settings" class="link">Settings</a>
    <a class="link" href="{{BASEURL}}/students/mainpage"><i class="material-icons" style="">home</i>Home</a>
    <a class="link arrowBack" href="javascript:history.back()">{{--"{{BASEURL}}/">--}}<i style="font-size: 2.5rem;" class="material-icons">arrow_back</i></a>
    <form action="{{BASEURL}}/users/logout" method="POST" style="margin-left:auto">
        @csrf
        <input class="link" type="submit" value="Sign out" style="">
    </form>
    {{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
</div>
