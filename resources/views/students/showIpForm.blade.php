<x-layout>
    <title>Setting IP address</title>
    <script src="{{BASEURL}}/js/confirmWindow.js"></script>
</head>
<body>
    <div class="flexboxCenterer">
        <div class="form" style="width: 50%">
            <div style="color:red">You are only allowed to watch videos from up to 2 locations. When the hash of your IP address is stored in database, you can't replace it
                with another one. Please log out if you're not currently connected through your home router.
            </div>
            <div id="ipQuestion" style="display:flex; gap:2rem">
                <button class="link" onclick="showIpConfirmation({{$studentsIP}})">One of my 2 IP address will be {{$studentsIP}} forever</button>
                <form action="{{BASEURL}}/users/logout" method="POST" style="">
                    @csrf
                    <input class="link" type="submit" value="Log out" style="width:14rem; height:6rem; font-size:4rem">
                </form>
            </div>
        </div>
    </div>
</x-layout>
