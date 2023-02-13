{{--@if(count($courses)>0)--}}
<link rel="stylesheet" href="{{BASEURL}}/css/videoGrid.css">
    @if(count($courses)>0)
        <table>
            <tr>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Faculty</th>
            </tr>
        @foreach ($courses as $course)
            <tr>
                <td>{{$course->name}}</td>
                <td>{{$course->teacher}}</td>
                <td>{{$course->faculty}}</td>
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
