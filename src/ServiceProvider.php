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
        if($this->shouldRegisterDumpServerChecker()) {
            $this->registerDumpServerChecker();
        }
    }

    /**
     * Returns whether or not the dump server checker should be bound.
     *
     * @return boolean
     */
    protected function shouldRegisterDumpServerChecker()
    {
        return (
            class_exists('BeyondCode\\DumpServer\\RequestContextProvider') &&
            class_exists('Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\SourceContextProvider') &&
            class_exists('Symfony\\Component\\VarDumper\\Server\\Connection')
        );
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
        if($this->shouldRegisterDumpServerChecker()) {
            return [];
        }

        return [
            Check::class
        ];
    }
}
