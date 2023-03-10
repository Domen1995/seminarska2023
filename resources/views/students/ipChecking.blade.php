<script>
    //const conn = new WebSocket("wss://127.0.0.1:443/robots/")
    //const conn = new WebSocket("wss://192.168.0.20:443/robots/")
    //DELUJE: const conn = new WebSocket("wss://192.168.43.170:443/robots/")
    const conn = new WebSocket("{{WEBSOCKET_URL}}")
    /*const studentName = "{{$student->name}}"
    const studentEmail = "{{$student->email}}"*/
   /* const studentInfo = {
        name : studentName,//"{{$student->name}}+'"',
        //ip : "$ip",

    }*/

    conn.onopen = function(e){
        const informingPresence = {
            type : "student_joined",
            studentId : "{{$student->id}}",
            token : "{{$token}}"  // webSocket token
        }
            /*name : studentName,
            email : studentEmail*/
            //info : JSON.stringify(studentInfo)
        conn.send(JSON.stringify(informingPresence))
    }

    conn.onmessage = function(e){
        const data = JSON.parse(e.data)
        console.log(data.type)
        if(data.type=="checkingFinished"){
            if(data.redirect!=null){
                location = data.redirect
            }
        }
    }

    conn.onclose = function(e){
        console.log("connection closed")
    }
</script>
