<?php

namespace Fyre\Engine\Logger;

use
    Config\Services,
    Fyre\Engine\Logger\Exceptions\LoggerException;

use const
    FILE_APPEND,
    LOCK_EX;

use function
    array_key_exists,
    date,
    strtoupper,

    write_file;

class Logger implements LoggerInterface
{
    private $config;

    private $types = [
        'CRITICAL' => 1,
        'ERROR' => 2,
        'ALERT' => 3,
        'WARNING' => 4,
        'DEBUG' => 5,
        'INFO' => 6
    ];

    public function __construct(LoggerConfig &$config)
    {
        $this->config = &$config;

        $this->debug('Logger class loaded');
    }

    public function alert(string $message): bool
    {
        return $this->logMessage('alert', $message);
    }

    public function critical(string $message): bool
    {
        return $this->logMessage('critical', $message);
    }

    public function debug(string $message): bool
    {
        return $this->logMessage('debug', $message);
    }

    public function error(string $message): bool
    {
        return $this->logMessage('error', $message);
    }

    public function info(string $message): bool
    {
        return $this->logMessage('info', $message);
    }

    public function logMessage(string $type, string $message): bool
    {
        $type = strtoupper($type);

        if ( ! array_key_exists($type, $this->types)) {
            LoggerException::typeInvalid($type);
        }

        if ($this->types[$type] > $this->config->threshold) {
            return false;
        }

        return write_file(
            $this->config->path.'/'.date('d-m-Y').'.php',
            '['.date('d-m-Y H:i:s').'] '.$type.' - '.$message."\r\n",
            FILE_APPEND | LOCK_EX
        );
    }

    public function warning(string $message): bool
    {
        return $this->logMessage('debug', $message);
    }

}
