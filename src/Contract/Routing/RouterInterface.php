<?php

declare(strict_types = 1);

namespace Everypay\Framework\Contract\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    public function dispatch(ServerRequestInterface $request): void;

    public function getArguments(): array;

    /**
     * @return callable|string
     */
    public function getAction();
}