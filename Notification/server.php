<?php
include_once('Pusher.php');
require dirname(__DIR__) . '/vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$pusher = new Notification\Pusher();

$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://0.0.0.0:4545'); 
$pull->on('message', array($pusher, 'onPull'));

$webSock = new React\Socket\Server('0.0.0.0:8082', $loop); 
$webServer = new Ratchet\Server\IoServer(  
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                $pusher
            )
        )
    ),
    $webSock
);

$loop->run();
?>