{{--@if(count($courses)>0)--}}
<link rel="stylesheet" href="{{BASEURL}}/css/videoGrid.css">
    @if(count($courses)>0)
        <table class="courseList">
            <caption>Courses</caption>
            <tr>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Faculty</th>
            </tr>
        @foreach ($courses as $course)
            <tr>
                <td><a href="{{BASEURL}}/videos/courseVideos/{{$course->id}}">{{$course->name}}</a></td>
                <td>{{$course->teacher}}</td>
                <td>{{$course->faculty}}</td>
                @if(!$user->isTeacher)
                    <td><a href="{{BASEURL}}/students/enroll">Request enrollment</a></td>
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
