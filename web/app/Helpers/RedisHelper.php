<?php

class RedisHelper {
    public $client;

    public function __construct() {
        Predis\Autoloader::register();
        $parameters = [
            'tcp://' . $_ENV['REDIS_HOST'] . ":" . $_ENV['REDIS_PORT']
        ];
        $options = [
            'parameters' => [
                'password' => $_ENV['REDIS_PASSWORD']
            ],
        ];
        $this->client = new Predis\Client($parameters, $options);
    }

    public function get($key) {
        $cachedOffice = $this->client->get($key);
        return $cachedOffice;
    }

    public function set($key, $value) {
        $this->client->set($key, $value);
    }
}
