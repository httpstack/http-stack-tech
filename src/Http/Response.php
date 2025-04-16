<?php

namespace Httpstack\Http;
use Httpstack\Http\Interfaces\ResponseInterface;
/**
 * Class Response
 * @package Httpstack\Http
 *
 * This class implements the ResponseInterface and provides methods to handle HTTP responses.
 */

class Response implements ResponseInterface {
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';

    public function setStatusCode(int $code): void {
        $this->statusCode = $code;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function setHeader(string $name, string $value): void {
        $this->headers[$name] = $value;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function setBody(string $content): void {
        $this->body = $content;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function send(): void {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}