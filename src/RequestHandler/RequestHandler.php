<?php

declare(strict_types = 1);

namespace Everypay\Framework\RequestHandler;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class RequestHandler implements RequestHandlerInterface
{
    private array $queue;
    private $resolver;

    public function __construct(array $queue, MiddlewareResolverInterface $resolver = null)
    {
        if (empty($queue)) {
            throw new InvalidArgumentException('$queue cannot be empty');
        }

        $this->queue = $queue;

        if ($resolver === null) {
            $resolver = function ($entry) {
                return $entry;
            };
        }

        $this->resolver = $resolver;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $entry = current($this->queue);
        $middleware = $this->resolver->resolve($entry);
        next($this->queue);

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }

        throw new RuntimeException(
            sprintf(
                'Invalid middleware queue entry: %s. Middleware must implement %s.',
                $middleware,
                MiddlewareInterface::class
            )
        );
    }
}
