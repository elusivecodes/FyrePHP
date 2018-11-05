<?php

namespace Fyre\Engine\Response;

interface ResponseInterface
{

    public function _render(): void;
    public function charset(): string;
    public function contentType(string $type, string $charset = null): ResponseInterface;
    public function getStatusCode(): int;
    public function header(string $header, string $content, bool $replace = true): ResponseInterface;
    public function json($data): ResponseInterface;
    public function redirect(string $url, bool $permanent = false): void;
    public function reset(): ResponseInterface;
    public function send(string $string): ResponseInterface;
    public function setCSP(): ResponseInterface;
    public function setStatusCode(int $statusCode): ResponseInterface;

}
