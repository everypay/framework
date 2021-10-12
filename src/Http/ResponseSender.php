<?php

declare(strict_types=1);

namespace Everypay\Framework\Http;

use Psr\Http\Message\ResponseInterface;

class ResponseSender
{
    public static function send(ResponseInterface $response): string
    {
        if (!headers_sent()) {
            header(
                sprintf(
                    'HTTP/%s %s %s',
                    $response->getProtocolVersion(),
                    $response->getStatusCode(),
                    $response->getReasonPhrase()
                ),
                true,
                $response->getStatusCode()
            );

            foreach ($response->getHeaders() as $header => $values) {
                foreach ($values as $value) {
                    header(
                        sprintf(
                            '%s: %s',
                            $header,
                            $value
                        ),
                        false,
                        $response->getStatusCode()
                    );
                }
            }
        }

        return $response->getBody()->__toString();
    }
}
