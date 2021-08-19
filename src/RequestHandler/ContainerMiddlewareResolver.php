<?php

declare(strict_types = 1);

namespace Everypay\Framework\RequestHandler;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use RuntimeException;

class ContainerMiddlewareResolver implements MiddlewareResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($entry): MiddlewareInterface
    {
        if (is_string($entry)) {
            $entry = $this->container->get($entry);
        }

        if ($entry instanceof MiddlewareInterface) {
            return $entry;
        }

        throw new RuntimeException(
            sprintf('Queue entries type must be string or instances of %s', MiddlewareInterface::class)
        );
    }
}