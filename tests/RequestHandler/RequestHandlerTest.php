<?php

namespace Everypay\Framework\RequestHandler;

use Everypay\Framework\Common\MiddlewareMock;
use Everypay\Framework\Common\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    public function testShouldHandleRequest(): void
    {
        $serverRequestFactory = new ServerRequestFactory();
        $request = $serverRequestFactory->createServerRequest('GET', 'https://testingnotexistingdomain.abc' );
        $middleware = new MiddlewareMock();
        $queue[] = $middleware;
        $requestHandler = new RequestHandler($queue);
        $response = $requestHandler->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertInstanceOf('Laminas\Diactoros\Response', $response);
    }
}
