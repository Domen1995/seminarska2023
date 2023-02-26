<script>
    const conn = new WebSocket("wss://127.0.0.1:443/robots/")
    const studentName = "{{$student->name}}"
    const studentInfo = {
        name : studentName,//"{{$student->name}}+'"',
        ip : "{{$ip}}"
    }

    conn.onopen = function(e){
        const informingPresence = {
            type : "student_joined",
            info : JSON.stringify(studentInfo)
        }
        conn.send(JSON.stringify(informingPresence))
    }
</script>
