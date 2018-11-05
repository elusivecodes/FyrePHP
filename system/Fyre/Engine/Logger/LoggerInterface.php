<?php

namespace Fyre\Engine\Logger;

interface LoggerInterface
{

    public function alert(string $message): bool;
    public function critical(string $message): bool;
    public function debug(string $message): bool;
    public function error(string $message): bool;
    public function info(string $message): bool;
    public function logMessage(string $type, string $message): bool;
    public function warning(string $message): bool;

}
