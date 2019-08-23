<?php

namespace Stasyanko\LaravelBeeQueue;

interface LaravelBeeQueueInterface
{
    /**
     * createJob
     *
     * @param  string $data Job data serialized to json string
     * @param  array $options
     *
     * @return void
     */
    public function createJob(string $data, array $options);
}
