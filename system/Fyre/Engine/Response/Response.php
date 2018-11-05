<?php

namespace Fyre\Engine\Response;

use
    Closure,

    Config\Services,
    Fyre\Engine\Response\Exceptions\ResponseException;

use const
    APP_PATH;

use function
    array_key_exists,
    extract,
    header,
    ini_set,
    is_array,
    json_encode,
    set_header,
    str_replace,

    log_message;

class Response implements ResponseInterface
{
    private $config;

    private $statusCode;
    private $headers;
    private $body;

    public function __construct(ResponseConfig &$config)
    {
        $this->config = &$config;

        if ($this->config->cspConfig) {
            $cspConfig = Services::config('Engine\ContentSecurityPolicy');
        } else {
            $cspConfig = new ContentSecurityPolicyConfig();
        }
        $this->csp = new ContentSecurityPolicy($cspConfig);

        if ($this->config->compression) {
            ini_set('zlib.output_compression', 1);
            ini_set(
                'zlib.output_compression_level',
                (int) $this->config->compressionLevel
            );
        }

        $this->reset();

        Services::logger()->debug('Response class loaded');
    }

    public function charset(): string
    {
        return $this->config->charset;
    }

    public function contentType(string $type, string $charset = null): ResponseInterface
    {
        $this->header('Content-Type', $type.($charset ? '; charset='.$charset : ''));

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function header(string $header, string $content, bool $replace = true): ResponseInterface
    {
        if ($replace || ! array_key_exists($header, $this->headers)) {
            $this->headers[$header] = $content;
        }

        return $this;
    }

    public function json($data): ResponseInterface
    {
        $this->contentType(
            'application/json',
            $this->config->charset
        );

        $this->send(
            json_encode($data)
        );

        return $this;
    }

    public function redirect(string $url, bool $permanent = false): void
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit;
    }

    public function reset(): ResponseInterface
    {
        $this->statusCode = 200;
        $this->headers = [];
        $this->body = [];

        return $this->header('Server', 'FrostFyre');
    }

    public function send(string $string): ResponseInterface
    {
        $this->body[] = $string;

        return $this;
    }

    public function setCSP(): ResponseInterface
    {
        $csp = $this->csp->buildCSP();

        if ($csp) {
            $this->header('Content-Security-Policy', $csp);
        }

        return $this;
    }

    public function setStatusCode(int $statusCode): ResponseInterface
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function _render(): void
    {
        http_response_code($this->statusCode);

        if ($this->config->csp) {
            $this->setCSP();
        }

        foreach ($this->headers AS $header => $content) {
            header($header.': '.$content);
        }

        foreach ($this->body AS $body) {
            echo $body;
        }
    }

}
