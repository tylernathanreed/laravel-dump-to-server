<?php

namespace Reedware\LaravelDumpToServer\Tests;

use Illuminate\Container\Container;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Reedware\LaravelDumpToServer\Check;
use Reedware\LaravelDumpToServer\ServiceProvider;

class DumpServerCheckTest extends TestCase
{
    public function test_check_is_online_returns_false()
    {
        $container = Container::getInstance();

        $container->singleton('config', function() {

            $config = m::mock('Illuminate\Contracts\Config\Repository');

            $config->shouldReceive('get')
                ->andReturn('tcp://127.0.0.1:9912');

            return $config;

        });

        $container->singleton('request', function() {
            return m::mock('Illuminate\Http\Request');
        });

        $container->instance('path.base', __DIR__);

        (new ServiceProvider($container))->register();

        $check = $container->make(Check::class);

        $this->assertFalse($check->isOnline());
    }
}