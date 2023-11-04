<?php

namespace Spark\Core\Support;

use Spark\Core\SparkInterface;

abstract class ServiceProvider
{
    /**
     * The spark instance.
     *
     * @var SparkInterface $spark
     */
    protected SparkInterface $spark;

    public function __construct(SparkInterface $spark)
    {
        $this->spark = $spark;
    }

    /**
     * Registers any services into application.
     *
     * @return void
     */
    public function register(): void
    {
    }
}
