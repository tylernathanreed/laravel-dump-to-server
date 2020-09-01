<?php

namespace Reedware\LaravelDumpToServer;

use BeyondCode\DumpServer\RequestContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Server\Connection;

class ServiceProvider extends DeferredServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDumpServerChecker();
    }

    /**
     * Registers the primary api connection bindings.
     *
     * @return void
     */
    protected function registerDumpServerChecker()
    {
        $this->app->singleton(Check::class, function($app) {

            $connection = new Connection($app->config->get('debug-server.host'), [
                'request' => new RequestContextProvider($app['request']),
                'source' => new SourceContextProvider('utf-8', $app['path.base']),
            ]);

            return new Check($connection);

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Check::class
        ];
    }
}