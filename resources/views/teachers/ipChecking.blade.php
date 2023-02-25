<x-layout>
    <title>Checking student presence</title>
</head>
<body>
    <script>
        //const conn = new WebSocket('wss://ratchet.192.168.0.20:8888/wss2/NNN')
        const conn = new WebSocket('wss://127.0.0.1:443/robots/')//('wss://127.0.0.1:4111/')

        conn.onopen = function(e){
            console.log("connected")
            setTimeout(pingMessage, 5000)
        }
        conn.onmessage = function(e){
            console.log("message arrived")
            const data = JSON.parse(e.data)
            //console.log(e.data)
            console.log(data.type)
            /*const data = JSON.parse(e.data)
            console.log(data.data)*/
        }

        conn.onclose = function(e){
            console.log("connection closed")
        }

        function pingMessage(){
            const msg = {type : "ping"}
            conn.send(JSON.stringify(msg))
            setTimeout(pingMessage, 5000)
        }
    </script>
</x-layout>
