<script>
    const conn = new WebSocket("wss://127.0.0.1:443/robots/")

    conn.onopen = function(e){
        const informingPresence = {
            type : "studentJoined"
        }
        conn.send()
    }
</script>
