<?php


namespace Rebuild\Server;


use Swoole\Http\Server as SwooelHttpServer;
use Swoole\Server as SwooleServer;

class Server implements ServerInterface
{
    /**
     * @var SwooleServer
     */
    protected $server;

    /**
     * @var array
     */
    protected $onRequestCallbacks = [];

    public function init(array $config): ServerInterface
    {
        foreach ($config['servers'] as $server) {
            $this->server = new SwooelHttpServer($server['host'], $server['port'], $server['type'], $server['sock_type']);
            $this->registerSwooleEvents($server['callbacks']);

            break;
        }
        return $this;
    }

    public function start()
    {
        $this->getServer()->start();
    }

    public function getServer()
    {
        return $this->server;
    }

    public function registerSwooleEvents(array $callbacks): void
    {
        foreach ($callbacks as $swooleEvent => $callback) {
            [$class, $method] = $callback;
            $instance = new $class;
            $this->server->on($swooleEvent, [$instance, $method]);
        }
    }
}