<?php

namespace Fyre\Engine\Benchmark;

interface BenchmarkInterface
{

    public function addQuery(string $query): BenchmarkInterface;
    public function elapsed(?string $a = null, ?string $b = null, int $precision = 4): ?string;
    public function iterate(callable $callback, int $iterations = 100): float;
    public function mark(string $name): float;
    public function memory(string $format = 'MB', int $precision = 2, bool $real_usage = false): string;
    public function queries(): array;
    public function queryCount(): int;

}
