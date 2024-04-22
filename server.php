<?php
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use modelo\WebSocketNotifications as WebSocketNotifications;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketNotifications();
        )
    ),
    8080
);

$server->run();