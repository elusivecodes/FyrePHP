<?php

namespace Fyre\Component\Encryption;

interface EncryptionHandlerInterface
{

    public function decrypt(string $data, string $key);
    public function encrypt($data, string $key): string;
    public function generateKey(int $length = null): string;

}
