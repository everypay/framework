<?php

declare(strict_types = 1);

namespace Everypay\Framework\RequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $queue;
    private MiddlewareResolverInterface $resolver;

    public function __construct(array $queue, MiddlewareResolverInterface $resolver = null)
    {
        if (empty($queue)) {
            throw new EmptyQueueException('$queue cannot be empty');
        }

        $this->queue = $queue;

        if ($resolver === null) {
            $resolver = new class() implements MiddlewareResolverInterface {
                public function resolve($entry): MiddlewareInterface
                {
                    return $entry;
                }
            };
        }

        $this->resolver = $resolver;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $entry = current($this->queue);
        $middleware = $this->resolver->resolve($entry);
        next($this->queue);

        return $middleware->process($request, $this);
    }
}
