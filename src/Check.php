<?php

namespace Reedware\LaravelDumpToServer;

use BeyondCode\DumpServer\RequestContextProvider;
use ReflectionClass;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Server\Connection;

class Check
{
    /**
     * The dump server connection.
     *
     * @var \Symfony\Component\VarDumper\Server\Connection
     */
    protected $connection;

    /**
     * The connection socket.
     *
     * @var {stream resource}
     */
    protected $socket;

    /**
     * Creates a new check instance.
     *
     * @param  \Symfony\Component\VarDumper\Server\Connection  $connection
     *
     * @return $this
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns whether or not the dump server is online.
     *
     * @return boolean
     */
    public function isOnline()
    {
        set_error_handler([self::class, 'nullErrorHandler']);

        try {

            if($this->sendCheckStatus()) {
                return true;
            }

            $this->rebootConnectionSocket();

            if($this->sendCheckStatus()) {
                return true;
            }

        }

        finally {
            restore_error_handler();
        }

        return false;
    }

    /**
     * Does nothing.
     *
     * @return void
     */
    public static function nullErrorHandler()
    {
        // no-op
    }

    /**
     * Sends the check status to the socket.
     *
     * @return booleam
     */
    protected function sendCheckStatus()
    {
        return stream_socket_sendto($this->socket(), 'checkAlive') !== -1;
    }

    /**
     * Returns the socket for the connection.
     *
     * @return {stream resource}
     */
    public function socket()
    {
        return !is_null($this->socket)
            ? $this->socket
            : ($this->socket = $this->resolveConnectionSocket());
    }

    /**
     * Resolves the socket for the connection.
     *
     * @return {stream resource}
     */
    protected function resolveConnectionSocket()
    {
        return tap((new ReflectionClass($this->connection))->getMethod('createSocket'), function($m) {
            $m->setAccessible(true);
        })->invoke($this->connection);
    }

    /**
     * Resolves the socket for the connection.
     *
     * @return {stream resource}
     */
    protected function rebootConnectionSocket()
    {
        if(!is_null($socket = $this->socket)) {

            stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
            fclose($socket);

        }

        return $this->socket = $this->resolveConnectionSocket();
    }
}