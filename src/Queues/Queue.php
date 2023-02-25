<?php

namespace App\Queues;

use App\Jobs\JobInterface;
use App\Storage\Reader;
use App\Storage\Writer;

final class Queue
{
    private const QUEUE_PREFIX = 'queue';
    private const STORAGE_PATH = __DIR__ . '/../../storage/';

    /**
     * @param JobInterface $job
     * @return void
     */
    public static function dispatch(JobInterface $job): void
    {
        $key = sprintf('%s.%s', str_replace('\\', '_', get_class($job)), self::QUEUE_PREFIX);

        if(file_exists(self::STORAGE_PATH . $key) === true) {
            (new Writer())->delete($key);
        }

        (new Writer())->create($key, serialize($job));
    }

    /**
     * @return array
     */
    public static function getQueues(): array
    {
        $queues = [];
        $queueFiles = array_diff(scandir(self::STORAGE_PATH), ['..', '.']);

        foreach ($queueFiles as $queue) {
            $ext = pathinfo($queue, PATHINFO_EXTENSION);

            if ($ext !== self::QUEUE_PREFIX) {
                continue;
            }

            /** Implementing job class with properties */
            $class = str_replace('_', '\\', pathinfo($queue, PATHINFO_FILENAME));
            $queueContent = (new Reader())->read($queue);
            $properties = (array)unserialize($queueContent, ['allowed_classes' => true]);
            $queues[] = new $class(...array_values($properties));
        }

        return $queues;
    }

    /**
     * @param string $class
     * @return JobInterface
     */
    public static function getQueue(string $class): JobInterface
    {
        /** Implementing job class with properties */
        $key = sprintf('%s.%s', str_replace('\\', '_', $class), self::QUEUE_PREFIX);
        $queueContent = (new Reader())->read($key);
        $properties = (array)unserialize($queueContent, ['allowed_classes' => true]);

        return new $class(...array_values($properties));
    }
}