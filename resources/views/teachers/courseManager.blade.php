<div class="courseManager">
    <div class="flexboxCenterer">
        <div class="form" style="justify-content:start; align-items:center; width:50%; height:8rem">
            <a class="link" href="{{BASEURL}}/teachers/uploadForm/{{$course->id}}" style="font-size: 3rem; font-family:inherit">Upload a video to {{$course->name}}</a>
            <a href="{{BASEURL}}/teachers/checkIp/{{$course->id}}" class="link">Start checking which students will connect to your IP address</a>
            <a href="/teachers/manageStudents/{{$course->id}}">Manage enrolled students</a>
            <div class="enrollmentRequests">
                {{--<div>Students who want to enroll to this course:</div>--}}
                {{--@if($studentsToEnroll!=null)--}}
                @if(count($studentsToEnroll)>0)
                    @include('courses.studentsToEnroll')
                @endif
            </div>
        </div>
    </div>
</div>
