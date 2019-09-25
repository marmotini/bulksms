<?php namespace bulksms;

include_once "autoloader.php";

use \PhpAmqpLib\Exchange\AMQPExchangeType;
use \PhpAmqpLib\Message\AMQPMessage;

$exchange = 'bench_exchange';
$queue = 'bench_queue';

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection("localhost", 5672, "guest", "guest", "/");
$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, false);

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, false, false);

$channel->queue_bind($queue, $exchange);


$messageBody = <<<EOT
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz
abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyza
EOT;

$message = new AMQPMessage($messageBody);

$time = microtime(true);

$max = isset($argv[1]) ? (int) $argv[1] : 1;
$batch = isset($argv[2]) ? (int) $argv[2] : 2;

// Publishes $max messages using $messageBody as the content.
for ($i = 0; $i < $max; $i++) {
    $channel->batch_basic_publish($message, $exchange);

    if ($i % $batch == 0) {
        $channel->publish_batch();
    }
}

$channel->publish_batch();

echo microtime(true) - $time, "\n";

$channel->basic_publish(new AMQPMessage('quit'), $exchange);

$channel->close();
$connection->close();
