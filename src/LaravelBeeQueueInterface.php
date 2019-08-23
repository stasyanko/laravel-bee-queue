<?php

namespace Stasyanko\LaravelBeeQueue;

interface LaravelBeeQueueInterface
{
    /**
     * createJob
     *
     * @param  array $data Job data serialized to json string
     * @param  array $options
     *
     * @return void
     */
    public function createJob(array $data, array $options);
}
