<?php
include_once('ForkRepo.php');

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Notification\ForkRepo;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ForkRepo()
        )
    ),
    8080
);
$server->run();
?>