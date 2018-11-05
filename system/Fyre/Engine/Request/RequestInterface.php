<?php

namespace Fyre\Engine\Request;

interface RequestInterface
{

    public function body(): string;
    public function cookie(string $key);
    public function get(string $key);
    public function isAjax(): bool;
    public function isCLI(): bool;
    public function json(bool $makeArray = false);
    public function method(): string;
    public function negotiateLocale(): string;
    public function post(string $key);
    public function segment(int $n): ?string;
    public function segmentArray(): array;
    public function segmentCount(): int;
    public function server(string $key);
    public function uriString(): string;
    public function xml();

}
