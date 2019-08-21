<?php

namespace Stasyanko\LaravelBeeQueue;

class LaravelBeeQueue implements LaravelBeeQueueInterface
{
    private $queueName;
    private $defaults = [
        'stallInterval' => 5000,
        'nearTermWindow' => 20 * 60 * 1000,
        'delayedDebounce' => 1000,
        'prefix' => 'bq',
        'isWorker' => true,
        'getEvents' => true,
        'ensureScripts' => true,
        'activateDelayedJobs' => false,
        'sendEvents' => true,
        'storeJobs' => true,
        'removeOnSuccess' => false,
        'removeOnFailure' => false,
        'redis' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'db' => 0,
            'options' => []
        ],
        'redisScanCount' => 100,
        '#close' => [
            'timeout' => 5000
        ],
        '#process' => [
            'concurrency' => 1
        ]
    ];

    public function __construct(string $queueName, array $settings = [])
    {
        $this->queueName = $queueName;

        $this->settings = [
            'redis' => $settings['redis'] ?? $this->defaults['redis'],
            'quitCommandClient' => $settings['quitCommandClient'] ?? false,
            'keyPrefix' => $settings['prefix'] ?? $this->defaults['prefix'] . ':' . $queueName . ':',
        ];
    }

    public function createJob(string $data, array $settings = [])
    {
        //TODO: complete function
        dd($this->settings);
    }

    private function toKey($str)
    {
        return $this->settings['keyPrefix'] . $str;
    }
}
