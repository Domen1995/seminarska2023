{{--@if(count($courses)>0)--}}
{{--<link rel="stylesheet" href="{{BASEURL}}/css/videoGrid.css">--}}
<link rel="stylesheet" href="/css/tables.css">
    @if(count($courses)>0)
        <table class="courseList">
            <caption>Courses</caption>
            <tr>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Faculty</th>
                {{--
                <th>My presences</th>
                <th>My screw-ups</th>--}}
                <th></th>
            </tr>
        @foreach ($courses as $course)
            <tr>
                <td>{{--<a href="{{BASEURL}}/videos/courseVideos/{{$course->id}}">--}}{{$course->name}}{{--</a>--}}</td>
                <td>{{$course->teacher}}</td>
                <td>{{$course->faculty}}</td>
                @if(!$user->isTeacher)
                    @if(isset($coursesUsers))
                        {{--@php
                            $GLOBALS['currentCourse'] = 0;
                            foreach ($coursesUsers as $courseUser) {
                                if($courseUser->course_id == $course->id){
                                    break;
                                }
                                $GLOBALS['currentCourse']++;
                            }
                            //dd($coursesUsers[0]->course_id)

                        @endphp--}}
                        @if(!isset($coursesUsers[$course->id]))
                            <td><a href="{{BASEURL}}/students/enrollment/request/{{$course->id}}">Request enrollment</a></td>
                        @else
                            {{--
                            <td>{{$coursesUsers[$course->id]->presences}}</td>
                            <td>{{$coursesUsers[$course->id]->screwUps}}</td>--}}
                        {{--@switch($coursesUsers[$GLOBALS['currentCourse']]->status)--}}
                            @switch($coursesUsers[$course->id]->status)
                           {{-- @switch($coursesUsers)--}}
                            @case('requested')
                                <td><a href="{{BASEURL}}/students/enrollment/delete/{{$course->id}}">Delete request</a></td>
                                @break
                            @case('approved')
                                @break
                            @default
                                <td><a href="{{BASEURL}}/students/coursePage/{{--/videos/courseVideos/--}}{{$course->id}}">Watch videos</a></td>
                        @endswitch
                        @endif
                    @else
                        <td><a href="{{BASEURL}}/students/enrollment/request/{{$course->id}}">Request enrollment</a></td>
                    @endif
                @elseif ($course->user_id== $user->id)
                    <td><a href="{{BASEURL}}/teachers/coursePage/{{$course->id}}">Go to course</a></td>
                @endif
            </tr>
        @endforeach
        </table>

        <div class="flexboxCenterer">
            <div class="pageList">
                {{$courses->links('pagination::bootstrap-4')}}
            </div>
        </div>
    @endif

    {{--
    <div style="margin-left: auto; margin-right:auto; width:15rem; text-align:center; background-color:#4b3268;font-size:2rem; border-radius:.3rem">Your creations: </div>
    @include('videos.videoGrid')--}}
{{--@endif--}}
