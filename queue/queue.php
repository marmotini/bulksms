<?php namespace bulksms\queue;

use bulksms\provider\Message;
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Exchange\AMQPExchangeType;
use \PhpAmqpLib\Message\AMQPMessage;

use bulksms\Config;

class Queue
{
    private static $config;
    private static $connection;
    private static $channel;

    public static function setup()
    {
        self::$config = Config::get('rabbitmq');
        $connection = new AMQPStreamConnection(
            self::$config->host,
            self::$config->port,
            self::$config->user,
            self::$config->pass,
            self::$config->vhost);

        self::$channel = $connection->channel();

        self::$channel->queue_declare(self::$config->queue, false, true, false, false);
        self::$channel->exchange_declare(self::$config->exchange, AMQPExchangeType::DIRECT, false, true, false);
        self::$channel->queue_bind(self::$config->queue, self::$config->exchange);

    }

    public static function consume($cb)
    {
        self::$channel->basic_consume(
            self::$config->queue,
            self::$config->consumertag,
            false,
            false,
            false,
            false,
            $cb);
    }

    public static function publish(Message $msg)
    {
        $message = new AMQPMessage(
            json_encode($msg),
            array(
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
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

register_shutdown_function(Queue::shutdown);