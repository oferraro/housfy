<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
Predis\Autoloader::register();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$queue = 'offices';

$channel->queue_declare($queue, false, false, false, false);

if (env('APP_ENV')) {
    error_log(" [*] Waiting for messages. To exit press CTRL+C\n", 3, '/tmp/log');
}

$callback = function ($msg) {
    if (env('APP_ENV')) { // TODO: move this to a helper or use Laravel logger (it only logs in local environment)
        error_log(' [x] Received ', $msg->body, "\n", 3, '/tmp/log');
    }
    $office = json_decode($msg->body);

    $redisHelper = new RedisHelper();
    $office = $redisHelper->set('office:' . $office->id, $msg->body);

    if (env('APP_ENV')) {
        error_log('office:' . $office->id . "\n", 3, '/tmp/log');
    }
    $client->disconnect();
};

$channel->basic_consume($queue, '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
