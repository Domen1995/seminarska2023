<x-layout>
    <x-menu />
    <div class="flexboxCenterer">
        <div class="form" style="justify-content:start; align-items:center; height:20rem">
            {{--<input type="text" value="{{auth()->user()->name}}" disabled>--}}
            <div style="font-size:2rem; font-family:inherit">{{$user->name}}</div>
            <div>{{$user->description}}</div>
            {{--<input type="text" id="channelDescription" name="channelDescription" height="6rem">--}}
        </div>
    </div>
    @if(count($videos)>0)
    <div style="margin-left: auto; margin-right:auto; width:15rem; text-align:center; background-color:#4b3268;
                font-size:2rem; border-radius:.3rem">
                {{$user->name}} has uploaded:
    </div>
    @include('videos.videoGrid')
    @endif
    {{--    <title>User {{$user->name}}</title>
    </head>
    <body>
        <div class="">{{$user->name}}</div>--}}
    </x-layout>
