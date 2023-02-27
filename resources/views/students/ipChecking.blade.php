<script>
    //const conn = new WebSocket("wss://127.0.0.1:443/robots/")
    const conn = new WebSocket("wss://192.168.0.20:443/robots/")
    /*const studentName = "{{$student->name}}"
    const studentEmail = "{{$student->email}}"*/
   /* const studentInfo = {
        name : studentName,//"{{$student->name}}+'"',
        //ip : "$ip",

    }*/

    conn.onopen = function(e){
        const informingPresence = {
            type : "student_joined",
            studentId : "{{$student->id}}"
        }
            /*name : studentName,
            email : studentEmail*/
            //info : JSON.stringify(studentInfo)
        conn.send(JSON.stringify(informingPresence))
    }

    conn.onmessage = function(e){
        const data = JSON.parse(e.data)
        console.log(data.type)
    }

    conn.onclose = function(e){
        console.log("connection closed")
    }
</script>
