<?php

namespace Stasyanko\LaravelBeeQueue;

use Predis\Client;

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

    /**
     * __construct
     *
     * @param  string $queueName
     * @param  array $settings
     *
     * @return void
     */
    public function __construct(string $queueName, array $settings = [])
    {
        $this->queueName = $queueName;

        $this->settings = [
            'quitCommandClient' => true,
            'keyPrefix' => $settings['prefix'] ?? $this->defaults['prefix'] . ':' . $queueName . ':',
        ];
    }

    /**
     * createJob
     *
     * @param  array $data
     * @param  array $options
     *
     * @return void
     */
    public function createJob(array $data, array $options = [])
    {
        $client = new Client(config('laravel_bee_queue.redis'));
        //TODO: move to external method or class
        $addJobLuaScript = <<<'LUA'
--[[
key 1 -> bq:name:id (job ID counter)
key 2 -> bq:name:jobs
key 3 -> bq:name:waiting
arg 1 -> job id
arg 2 -> job data
]]

local jobId = ARGV[1]
if jobId == "" then
    jobId = "" .. redis.call("incr", KEYS[1])
end
if redis.call("hexists", KEYS[2], jobId) == 1 then return nil end
redis.call("hset", KEYS[2], jobId, ARGV[2])
redis.call("lpush", KEYS[3], jobId)

return jobId
LUA;

        $res = $client->eval($addJobLuaScript, 3, $this->toKey("id"), $this->toKey("jobs"), $this->toKey("waiting"), null, $this->getReadyPayload($data, $options));

        if ($res === null) {
            return null;
        } else {
            return (int) $res;
        }
    }

    /**
     * toKey
     *
     * @param  string $str
     *
     * @return void
     */
    private function toKey($str)
    {
        return $this->settings['keyPrefix'] . $str;
    }

    /**
     * getReadyPayload
     *
     * @param  array $data
     * @param  array $options
     *
     * @return void
     */
    private function getReadyPayload(array $data, array $options)
    {
        $readyOptions = [
            'timestamp' => $this->getCurTsMs(),
            'stacktraces' => [],
            'timeout' => $options['timeout'] ?? $this->defaults['stallInterval'],
            'retries' => $options['retries'] ?? 0,
        ];

        return json_encode([
            'data' => $data,
            'options' => $readyOptions,
            'status' => 'created',
        ]);
    }

    /**
     * getCurTsMs
     *
     * @return void
     */
    //TODO: move it to helpers
    private function getCurTsMs()
    {
        list($msec, $sec) = explode(' ', microtime());

        return $sec . substr($msec, 2, 6);
    }
}
