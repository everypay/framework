<?php

namespace Everypay\Framework\Common;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return (new \Laminas\Diactoros\ServerRequestFactory())
            ->createServerRequest($method, $uri, $serverParams);
    }
}
