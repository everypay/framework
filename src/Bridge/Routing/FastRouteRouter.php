<?php

declare(strict_types = 1);

namespace Everypay\Framework\Bridge\Routing;

use Everypay\Framework\Contract\Routing\HttpMethodNotAllowed;
use Everypay\Framework\Contract\Routing\HttpNotFoundException;
use Everypay\Framework\Contract\Routing\RouterInterface;
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

    public function dispatch(ServerRequestInterface $request): void
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new HttpNotFoundException($request->getUri()->getPath());
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new HttpMethodNotAllowed(
                    sprintf("Method %s not allowed in %s.", $request->getMethod(), $request->getUri()->getPath())
                );
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