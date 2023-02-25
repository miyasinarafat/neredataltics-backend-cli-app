<?php

namespace App\Jobs;

use App\Storage\Writer;

final class ProductCreateJob implements JobInterface
{
    public string $id;
    public string $name;
    public string $price;

    public function __construct(string $id, string $name, string $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function handle(): string
    {
        $product = sprintf('%s %s %s', $this->id, $this->name, $this->price);

        /** storing product on storage */
        (new Writer())->create($this->id, $product);

        return sprintf('Product created: %s', $product);
    }
}