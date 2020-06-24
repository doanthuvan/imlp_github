<?php

$context    = new ZMQContext();
$socket     = $context->getSocket(ZMQ::SOCKET_PUSH,'my pusher');
$socket->connect('tcp://localhost:4545');
$socket->send($argv[1]);
