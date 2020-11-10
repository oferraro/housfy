<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueController extends Controller
{
    const OFFICES_QUEUE = 'offices';

    function enqueueOfficeForCache($office)
    {
        // TODO: add rabbitmq and offices to .env
        $rabbitUser = 'guest';
        $rabbitPassword = 'guest';
        $connection = new AMQPStreamConnection(
            'rabbitmq', 5672, $rabbitUser, $rabbitPassword
        );
        $channel = $connection->channel();
        $channel->queue_declare(self::OFFICES_QUEUE, false, false, false, false);

        // TODO: enqueue this office for cache in consumer
        $msg = new AMQPMessage($office->toJson());
        $channel->basic_publish($msg, '', self::OFFICES_QUEUE);
        $channel->close();
        $connection->close();
    }

}
