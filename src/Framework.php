<?php

declare(strict_types = 1);

namespace Everypay\Framework;

use Everypay\Framework\RequestHandler\ContainerMiddlewareResolver;
use Everypay\Framework\RequestHandler\RequestHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class Framework
{
    private array $entries = [];
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string|MiddlewareInterface $middleware
     */
    public function add($middleware): void
    {
        $this->entries[] = $middleware;
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $requestHandler = new RequestHandler(
            $this->entries,
            new ContainerMiddlewareResolver($this->container)
        );

        return $requestHandler->handle($request);
    }
}