<?php

namespace App\Jobs;

interface JobInterface
{
    /**
     * @return string
     */
    public function handle(): string;
}