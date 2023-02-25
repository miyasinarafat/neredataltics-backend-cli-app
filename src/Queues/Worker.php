<?php

namespace App\Queues;

use App\Jobs\JobInterface;
use App\Jobs\ProductCreateJob;
use App\Jobs\ProductDeleteJob;
use App\Jobs\ProductUpdateJob;

final class Worker
{
    public static array $jobs = [
        ProductCreateJob::class,
        ProductUpdateJob::class,
        ProductDeleteJob::class,
    ];

    /**
     * @return array
     */
    public static function execute(): array
    {
        $data = [];
        $queues = Queue::getQueues();

        /** @var JobInterface $queue */
        foreach ($queues as $queue) {
            $data[] = ($queue)->handle();
        }

        return $data;
    }

    /**
     * @return array
     */
    public static function run(): array
    {
        $data = [];

        foreach (self::$jobs as $job) {
            $actualJob = Queue::getQueue($job);
            $data[] = ($actualJob)->handle();
        }

        return $data;
    }
}