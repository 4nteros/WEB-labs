<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager {
    private $channel;
    private $mainQueue = 'main_tasks';
    private $errorQueue = 'error_tasks';

    public function __construct() {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $this->channel = $connection->channel();
        
        $this->channel->queue_declare($this->mainQueue, false, true, false, false);
        $this->channel->queue_declare($this->errorQueue, false, true, false, false);
    }

    public function publish($data, $targetQueue = 'main') {
        $queue = ($targetQueue === 'main') ? $this->mainQueue : $this->errorQueue;
        $msg = new AMQPMessage(json_encode($data), ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, '', $queue);
    }

    public function consume(callable $callback) {
        $this->channel->basic_consume($this->mainQueue, '', false, false, false, false, function($msg) use ($callback) {
            $data = json_decode($msg->body, true);
            try {
                $callback($data);
                $msg->ack();
            } catch (Exception $e) {
                echo "❌ Ошибка! Перекидываем в очередь ошибок...\n";
                $this->publish($data, 'error');
                $msg->ack();
            }
        });

        while($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
}