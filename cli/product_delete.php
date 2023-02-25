<?php declare(strict_types=1);

require_once './vendor/autoload.php';

use App\Jobs\ProductDeleteJob;
use App\Queues\Queue;

/** Removing filename from arguments */
unset($argv[0]);

/** publishing queue to process it later */
Queue::dispatch(new ProductDeleteJob($argv[1]));
