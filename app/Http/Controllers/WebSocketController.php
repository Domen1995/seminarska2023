<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        //$this::$clients = new \SplObjectStorage;
        //cache(["clients" => $this->clients]);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        //$this::$clients->attach($conn);
        $response = ["type" => "connectionConfirmed"];
        //$response["type"] = "firstMsg";
        //foreach($this->clients as $client) $client->send("msg");
        //$conn->send("second msg");
        $conn->send(json_encode($response));
        //cache(["client" => $conn->resourceId]);
        //dd($this::$clients->count());
        //$conn->send(count($this->clients));
        //cache(["conn" => $conn]);
        //$toSend = ["data" => "123"];
        //$conn->send(json_encode($toSend));
        $response = ["type" => $conn->remoteAddress];
        $conn->send(json_encode($response));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg);

        if(isset($msg->type)){
            switch($msg->type){
                case 'ping':
                    //return;
                    $response = ["type" => "pong"];
                    $from->send(json_encode($response));
                    break;
                case 'student_joined':
                    $response = ["type" => "student_joined", "info" => /*$msg->info.",".*/$from->remoteAddress];
                    foreach($this->clients as $client){
                        $client->send(json_encode($response));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        //$this::$clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "Error {$e->getMessage()}";
        $conn->close();
    }
}
