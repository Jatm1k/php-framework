<?php

namespace Jatmy\Framework\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = [],
    ) {
        http_response_code($this->statusCode);
    }

    public function send(): void
    {
        ob_start();
        foreach($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;

        ob_end_flush();
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;
    }

    public function getHeader(string $header): ?string
    {
        return $this->headers[$header] ?? null;
    }

    public function setHeader(string $header, string $value): void
    {
        $this->headers[$header] = $value;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

}
