<div class="courseManager">
    <div class="flexboxCenterer">
        <div class="form" style="justify-content:start; align-items:center">
            <a class="link" href="{{BASEURL}}/teachers/uploadForm/{{$course->id}}" style="font-size: 3rem; font-family:inherit">Upload a video to {{$course->name}}</a>
            <div class="enrollmentRequests">
                <div>Students who want to enroll in this course:</div>
                @if($studentsToEnroll!=null)
                    <div style="display: flex">
                    @foreach ($studentsToEnroll as $student)
                        <div>{{$student->name}}</div>
                        <div>{{$student->email}}</div>
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
