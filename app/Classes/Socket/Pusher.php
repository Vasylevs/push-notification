<?php


namespace App\Classes\Socket;

use App\Classes\Socket\Base\BasePusher;
use ZMQ;
use ZMQContext;

class Pusher extends BasePusher
{
    /**
     * Send data to App\Console\Commands\PushServer
     * @param array $data
     * @throws \ZMQSocketException
     */
    public static function sendDataToServer(array $data){
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH,'my pusher');

        $socket->connect('tcp://127.0.0.1:4444');

        $data = json_encode($data);

        $socket->send($data);
    }

    /**
     * Send data users
     * @param $sendData (must be json format)
     */
    public function broadcast($sendData){
        $dataToSend = json_decode($sendData,true);

        $subscribedTopics = $this->getSubscribedTopic();

        if (isset($subscribedTopics[$dataToSend['topic_id']])){
            $topic = $subscribedTopics[$dataToSend['topic_id']];
            $topic->broadcast($dataToSend);
        }
    }
}
