<?php namespace bulksms\queue;

include_once "autoloader.php";
use \PhpAmqpLib\Exchange\AMQPExchangeType;

use bulksms\Config;

/**
 * RabbitMq wrapper implementation.
 *
 * Class Queue
 * @package bulksms\queue
 */
class Queue
{
    private static $config;
    private static $connection;

    // After a tcp amqp connection has been created, the active lightweight channels are used to
    // publish and consume messages from the queue. This enables faster response time since no new
    // tcp connection is going to be created.
    private static $channel;

    private static $consumerRunning = false;

    /**
     * Setup the queue by creating an amqp protocol connection and creating the queue
     * and exchanges if they don't exist. Ideally this should be preconfigured in the queue
     * server but in this case, this needs to be here to enable seamless testing.
     */
    public static function setup()
    {
        try {
            self::$config = Config::get('rabbitmq');

            // Create a connection
            self::$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
                self::$config->host,
                self::$config->port,
                self::$config->user,
                self::$config->pass,
                self::$config->vhost);

            // Create a channel
            self::$channel = self::$connection->channel();

            // Declare a queue
            self::$channel->queue_declare(self::$config->queue, false, true, false, false);

            // Declare an exchange
            self::$channel->exchange_declare(self::$config->exchange, AMQPExchangeType::DIRECT, false, true, false);

            // Bind the queue to the exchange
            self::$channel->queue_bind(self::$config->queue, self::$config->exchange);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Consumer listens to a queue and passes the received amqp message to the callback function passed in the
     * parameter. The consumer remains active listening to new incoming messages.
     *
     * @param $cb function callback
     */
    public static function consume($cb)
    {
        self::$consumerRunning = true;

        // Consume a message and pass it to the queue
        self::$channel->basic_consume(
            self::$config->queue,
            self::$config->consumertag,
            false,
            true,
            false,
            false,
            $cb);

        // If there are channels connected, keep on waiting for that incoming message.
        if (self::$consumerRunning) {
            while (self::$channel->is_consuming()) {
                self::$channel->wait();
            }

            self::$consumerRunning = false;
        }
    }

    /**
     * Publis method sends a message to the queue. The message will later be consumed by the dispatcher using the
     * queue consume feature.
     *
     * @param \bulksms\message\Message $msg
     */
    public static function publish(\bulksms\message\Message $msg)
    {
        // Create an amqp message and json encode the body.
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