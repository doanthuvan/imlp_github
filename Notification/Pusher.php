<?php
namespace Notification;
include_once('Model/Repo.php');
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Model\Repo;

class Pusher implements WampServerInterface {

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    protected $subscribedTopics = array();

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onPull($entry) {
        if (!array_key_exists('fork', $this->subscribedTopics)) {
            return;
        }
        $topic = $this->subscribedTopics['fork'];

        $repo = new Repo();
        $url = $repo->fork($entry);

        $topic->broadcast($url);
    }
}
?>