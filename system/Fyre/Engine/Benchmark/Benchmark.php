<?php

namespace Fyre\Engine\Benchmark;

use
    Config\Services;

use function
    array_key_exists,
    count,
    memory_get_usage,
    microtime,
    number_format,
    strtoupper,

    byte_format;

class Benchmark implements BenchmarkInterface
{
    private $config;
    private $start;
    private $times = [];
    private $queries = [];

    public function __construct(BenchmarkConfig &$config)
    {
        $this->config =& $config;
        $this->start = microtime(true);

        Services::logger()->debug('Benchmark class loaded');
    }

    public function addQuery(string $query): BenchmarkInterface
    {
        $this->queries[] = $query;

        return $this;
    }

    public function elapsed(?string $a = null, ?string $b = null, int $precision = 4): ?string
    {
        if ( ! $a) {
            $a = $this->start;
        } else if (array_key_exists($a, $this->times)) {
            $a = $this->times[$a];
        } else {
            Services::warning('Elapsed time mark does not exist: '.$a);
            return null;
        }

        if ( ! $b) {
            $b = microtime(true);
        } else if (array_key_exists($b, $this->times)) {
            $a = $this->times[$b];
        } else {
            Services::warning('Elapsed time mark does not exist: '.$b);
            return null;
        }

        return number_format(
            $b - $a,
            $precision,
            '.',
            ''
        );
    }

    public function iterate(callable $callback, int $iterations = 100): float
    {
        $start = microtime(true);

        for ($i = 0; $i < $iterations; $i++) {
            if ($callback($i) === false) {
                break;
            }
        }

        return microtime(true) - $start;
    }

    public function mark(string $name): float
    {
        return array_key_exists($name, $this->times) ?
            $this->times[$name] :
            $this->times[$name] = microtime(true);
    }

    public function memory(string $format = 'MB', int $precision = 2, bool $real_usage = false): string
    {
        return byte_format(
            memory_get_usage($real_usage),
            strtoupper($format),
            $precision
        );
    }

    public function queries(): array
    {
        return $this->queries;
    }

    public function queryCount(): int
    {
        return count($this->queries);
    }

}
