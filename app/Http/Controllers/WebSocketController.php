<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Course;
use App\Models\Ip_testing;
use Illuminate\Http\Request;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Cache;
use Ratchet\MessageComponentInterface;

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
        //$requestURL = $conn->httpRequest->getUri()->getQuery();
        //parse_str($requestURL, $requestArr);
        //Ip_testing::where('token', $requestArr['token'])->update(['websocketId' => $conn->resourceId]);
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
                case 'storeWSid':   // store id of websocket to DB table Ip_testings
                    Ip_testing::where('token', $msg->token)->update(["websocketId" => $from->resourceId]);
                case 'student_joined':
                    // only send through socket that belongs to the teacher
                    //$response = ["type" => "student_joined", "info" => /*$msg->info.",".*/$from->remoteAddress];
                    if(isset($msg->studentId)){
                        //$ipTesting = Ip_testing::where('user_id', $msg->studentId)->first();
                        $ipTesting = Ip_testing::where('token', $msg->token)->first();
                        $course = Course::where('id', $ipTesting->course_id)->first();
                        $ipMatching = $ipTesting->ip == $course->ipForChecking;
                        if($ipMatching){
                            //$student = User::find($msg->studenId);
                            $student = User::find($ipTesting->user_id);
                            $informTeacher = ["type" => "student_joined",
                                                "name"=> $student->name,
                                                "email"=> $student->email,
                                                "studentId" => $student->id
                                            ];
                            $teacherWSid = Ip_testing::where('user_id', $course->user_id)->first()->websocketId;
                            foreach($this->clients as $client){
                                if($client->resourceId == $teacherWSid){
                                    $client->send(json_encode($informTeacher));
                                    $informStudent = ["type" => "checkingFinished", "redirect" => "/students/ipCheckingSuccess"];
                                    $from->send(json_encode($informStudent));
                                    $this->clients->detach($from);
                                    break;
                                }
                        }
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        //$this->clear_DB_ip_data($conn);

        $ip_testing = Ip_testing::where('websocketId', $conn->resourceId)->first();
        if($ip_testing->is_tester) $this->clear_DB_ip_data($ip_testing);
        // če je to profesor in ga je vrglo ven... Ga vseeno ne brišemo. Profesor se lahko vedno prijavi nazaj noter in se izbriše prejšnji zapis
        // v ip_testings v bazi. Študent se ne more še 1x prijaviti
        /*
        // if disconnected WS is from the tester (teacher), delete all rows of this course in ip_testings table
        if($ip_testing->is_tester){
            Ip_testing::where('course_id', $ip_testing->course_id)->delete();
        }*/

        $this->clients->detach($conn);
        //$this::$clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "Error {$e->getMessage()}";
        $ip_testing = Ip_testing::where('websocketId', $conn->resourceId)->first();
        if($ip_testing->is_tester) $this->clear_DB_ip_data($ip_testing);
        //$this->clear_DB_ip_data($conn);
        /*
        $ip_testing = Ip_testing::where('websocketId', $conn->resourceId)->first();
        // if disconnected WS is from the tester (teacher), delete all rows of this course in ip_testings table
        if($ip_testing->is_tester){
            Ip_testing::where('course_id', $ip_testing->course_id)->delete();
        }*/
        $conn->close();
    }


    public function clear_DB_ip_data(Ip_testing $ip_testing/*ConnectionInterface $conn*/)
    {
        //$ip_testing = Ip_testing::where('websocketId', $conn->resourceId)->first();
        // if disconnected WS is from the tester (teacher), delete all rows of this course in ip_testings table
        //if($ip_testing->is_tester){
            //Ip_testing::where('course_id', $ip_testing->course_id)->delete();
        Course::where('id', $ip_testing->course_id)->update(['ipForChecking'=>null, 'isCurrentlyChecking'=>0]);
        //}
    }

    /*
    public function doesIpMatch($studentIp)
    {

    }*/
}
