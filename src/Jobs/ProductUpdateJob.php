<?php

namespace App\Jobs;

use App\Storage\Reader;
use App\Storage\Writer;

final class ProductUpdateJob implements JobInterface
{
    private string $id;
    private string $name;
    private string $price;

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

        /** tracking product changes */
        $productChangedData = [];
        $storedProduct = explode(' ', (new Reader())->read($this->id));
        foreach ($storedProduct as $index => $value) {
            if ($index === 1 && $value !== $this->name) {
                $productChangedData[] = $this->name;
            }
            if ($index === 2 && $value !== $this->price) {
                $productChangedData[] = $this->price;
            }
        }

        /** updating product on storage */
        (new Writer())->update($this->id, $product);

        return sprintf('Product updated: %s', implode(' ', $productChangedData));
    }
}