<div class="menu">
    {{--<div class="dropdown-menu">
        <a href="{{BASEURL}}/users/uploadForm" class="link">Upload your video</a>
    </div>--}}
{{--</div>--}}
<a href="{{BASEURL}}/teachers/selfProfile{{--auth()->id()--}}" class="link">{{auth()->user()->name}}'s profile</a>
<a href="{{BASEURL}}/teachers/studentPermissions" class="link">Student permissions</a>
<a class="link" href="{{BASEURL}}/teachers/mainpage"><i class="material-icons" style="">home</i>Home</a>
<form action="{{BASEURL}}/users/logout" method="POST" style="margin-left:auto">
    @csrf
    <input class="link" type="submit" value="Sign out" style="">
</form>
{{--<a href="{{BASEURL}}/users/logout">Sign out</a>--}}
</div>
