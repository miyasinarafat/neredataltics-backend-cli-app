<?php

namespace App\Jobs;

use App\Storage\Writer;

final class ProductDeleteJob implements JobInterface
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        /** deleting product from storage */
        (new Writer())->delete($this->id);

        return sprintf('Product deleted: %s', $this->id);
    }
}