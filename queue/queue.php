<?php namespace bulksms\queue;

use \PhpAmqpLib\Exchange\AMQPExchangeType;

use bulksms\Config;

class Queue
{
    private static $config;
    private static $connection;
    private static $channel;

    private static $consumerRunning = false;

    public static function setup()
    {
        try {
            self::$config = Config::get('rabbitmq');
            $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
                self::$config->host,
                self::$config->port,
                self::$config->user,
                self::$config->pass,
                self::$config->vhost,
            false,
                'AMQPLAIN',
                null,
                'en_US',
                60,
                60, null, false, 30);

            self::$channel = $connection->channel();

            self::$channel->queue_declare(self::$config->queue, false, true, false, false);
            self::$channel->exchange_declare(self::$config->exchange, AMQPExchangeType::DIRECT, false, true, false);
            self::$channel->queue_bind(self::$config->queue, self::$config->exchange);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public static function consume($cb)
    {
        self::$consumerRunning = true;

        self::$channel->basic_consume(
            self::$config->queue,
            self::$config->consumertag,
            false,
            false,
            false,
            false,
            $cb);

        if (self::$consumerRunning) {
            while (self::$channel->is_consuming()) {
                self::$channel->wait();
            }

            self::$consumerRunning = false;
        }
    }

    public static function publish(\bulksms\provider\Message $msg)
    {
        $message = new \PhpAmqpLib\Message\AMQPMessage(
            json_encode($msg),
            array(
                'content_type' => 'text/plain',
                'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT
            )
        );

        self::$channel->basic_publish($message, self::$config->exchange);
    }

    public static function shutdown()
    {
        self::$channel->close();
        self::$connection->close();
    }
}

//register_shutdown_function('Queue::shutdown');