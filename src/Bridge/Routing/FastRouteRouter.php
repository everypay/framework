<?php

declare(strict_types = 1);

namespace Everypay\Framework\Bridge\Routing;

use Everypay\Framework\Contract\Routing\RouterInterface;
use Everypay\Framework\Exceptions\HttpException;
use FastRoute\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;

class FastRouteRouter implements RouterInterface
{
    private Dispatcher $dispatcher;

    private array $arguments;

    /**
     * @var string|callable
     */
    private $action;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws HttpException
     */
    public function dispatch(ServerRequestInterface $request): void
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new HttpException(404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpException(405);
            case Dispatcher::FOUND:
                $this->action = $routeInfo[1];
                $this->arguments = $routeInfo[2];
        }
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return callable|string
     */
    public function getAction()
    {
        return $this->action;
    }
}