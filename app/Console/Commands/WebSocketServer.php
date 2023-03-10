<?php

namespace App\Console\Commands;

use App\Http\Controllers\WebSocketController;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Illuminate\Console\Command;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;


class WebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketController
                )
            ),
            4111//8888
        );
        $server->run();
    }
}
