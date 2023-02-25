<?php declare(strict_types=1);

require_once './vendor/autoload.php';

use App\Queues\Worker;

$output = Worker::run();

foreach ($output as $item) {
    echo "$item\n";
}