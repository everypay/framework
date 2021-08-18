<?php

declare(strict_types = 1);

namespace Everypay\Framework\RequestHandler;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareResolverInterface
{
    public function resolve($entry): MiddlewareInterface;
}
