<x-layout>
    <title>Checking student presence</title>
    <link rel="stylesheet" href="/css/tables.css">
</head>
<body>
    <form action="/teachers/submitPresentStudents/{{$course->id}}" method="POST">
        @csrf
        <table id="presentStudents" class="students">
            <caption>Students who joined</caption>
        </table>
        <br>
        <table class="students">
            <caption>Students enrolled in {{$course->name}}</caption>
            @foreach ($enrolledStudents as $enrolledStudent)
                <tr>
                    <td>{{$enrolledStudent->name}}</td>
                    <td>{{$enrolledStudent->email}}</td>
                    <td><a href="#">Add manually</a></td>
                </tr>
            @endforeach
        </table>
        <button type="submit">Submit data, no one else is coming</button>
    </form>
    <script>
        //const conn = new WebSocket('wss://ratchet.192.168.0.20:8888/wss2/NNN')
        // DELUJOČA: const conn = new WebSocket('wss://127.0.0.1:443/robots/')//('wss://127.0.0.1:4111/')
        //const conn = new WebSocket('wss://192.168.0.20:443/robots/')
        const conn = new WebSocket("wss://192.168.43.170:443/robots/")

        conn.onopen = function(e){
            console.log("connected")
            conn.send(JSON.stringify({type : "storeWSid", token : "{{$token}}"}))
            setTimeout(pingMessage, 20000)
        }

        conn.onmessage = function(e){
            console.log("message arrived")
            const data = JSON.parse(e.data)
            //console.log(e.data)
            console.log(data.type)
            if(data.type=="student_joined"){
                //if(data.info!=null){
                if(data.name!=null){
                    //const info = data.info//JSON.parse(data.info)
                    const studentName = data.name
                    const studentMail = data.email
                    const studentId = data.studentId
                    addStudentToTable(studentId, studentName, studentMail)
                    //const studentId
                    console.log(studentId, studentName, studentMail)

                    /*const studentName = info.name
                    console.log(studentName)*/
                    //document.getElementById("wsOutput").innerHTML = data.info
                }
            }
            //document.getElementById("wsOutput").innerHTML = e.data.type
            /*const data = JSON.parse(e.data)
            console.log(data.data)*/
        }

        conn.onclose = function(e){
            console.log("connection closed")
        }

        function pingMessage(){
            // every 20 seconds ping server to prevent automatic disconnecting
            const msg = {type : "ping"}
            conn.send(JSON.stringify(msg))
            setTimeout(pingMessage, 20000)
        }

        function addStudentToTable(id, name, email){
            const studentsTable = document.getElementById("presentStudents")
            const studentRecord = document.createElement("tr")
            studentRecord.innerHTML =
                `<td>${name}</td>
                <td>${email}</td>
                <input type="hidden" name="studentIds[]" value="${id}">`
            studentsTable.appendChild(studentRecord)
        }
    </script>
</x-layout>
