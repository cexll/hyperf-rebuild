<?php


namespace Rebuild\HttpServer;


use Swoole\Http\Request;
use Swoole\Http\Response;

class Server
{
    public function onRequest(Request $request, Response $response):void
    {
        $response->header("Content-type", "text/html; charset=utf8-8");
        $response->end("<h1>Hello World</h1>");
    }

}