<?php

namespace Spark\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Configures and returns a mock object with proper type-hinting
     *
     * @param class-string<T> $class
     * @param mixed ...$arguments
     *
     * @return T & MockInterface
     * @template T
     */
    public function mock(string $class, mixed ...$arguments): MockInterface
    {
        /** @var T & MockInterface */
        return \Mockery::mock($class, $arguments);
    }

    /**
     * Configures and returns a mock object for spying with proper type-hinting
     *
     * @param class-string<T> $class
     * @param mixed ...$arguments
     *
     * return T & MockInterface
     *
     * @template T
     */
    public function spy(string $class, mixed ...$arguments): MockInterface
    {
        /** @var T & MockInterface */
        return Mockery::spy($class, ...$arguments);
    }
}
