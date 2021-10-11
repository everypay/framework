<?php

declare(strict_types=1);

namespace Everypay\Framework\Exceptions;

use Exception;

class HttpException extends Exception
{
    private array $headers = [];

    private string $body = '';

    private array $status = [
        302 => 'Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        419 => 'Authentication Timeout',
        420 => 'Method Failure',
        421 => 'Enhance Your Calm',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'No Response',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        494 => 'Request Header Too Large',
        495 => 'Cert Error',
        496 => 'No Cert',
        497 => 'HTTP to HTTPS',
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network read timeout error',
        599 => 'Network connect timeout error'
    ];

    /**
     * @param int $statusCode
     * @param null $statusPhrase
     * @param array $headers
     */
    public function __construct(int $statusCode = 500, $statusPhrase = null, array $headers = [])
    {
        if (null === $statusPhrase && isset($this->status[$statusCode])) {
            $statusPhrase = $this->status[$statusCode];
        }
        parent::__construct($statusPhrase, $statusCode);

        $header  = sprintf('HTTP/1.1 %d %s', $statusCode, $statusPhrase);

        $this->addHeader($header);
        $this->addHeaders($headers);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $header
     * @return self
     */
    public function addHeader(string $header): HttpException
    {
        $this->headers[] = $header;

        return $this;
    }

    /**
     * @param array $headers
     * @return self
     */
    public function addHeaders(array $headers): HttpException
    {
        foreach ($headers as $key => $header) {
            if (!is_int($key)) {
                $header = $key.': '.$header;
            }

            $this->addHeader($header);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return self
     */
    public function setBody(string $body): HttpException
    {
        $this->body = $body;

        return $this;
    }
}