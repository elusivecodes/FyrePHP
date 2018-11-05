<?php

namespace Fyre\Component\Encryption\Handlers;

use
    Fyre\Component\Encryption\EncryptionHandler,
    Fyre\Component\Encryption\EncryptionHandlerInterface;

use function
    json_decode,
    json_encode,
    random_bytes,
    serialize,
    sodium_crypto_secretbox,
    sodium_crypto_secretbox_open,
    unserialize;

use const
    SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
    SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

class Sodium extends EncryptionHandler implements EncryptionHandlerInterface
{

    public function decrypt(string $data, string $key)
    {
        $data = unserialize($data);

        return json_decode(
            sodium_crypto_secretbox_open(
                $data['data'],
                $data['nonce'],
                $key
            )
        );
    }

    public function encrypt($data, string $key): string
    {
        $nonce = $this->generateKey(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        return serialize(
            [
                'data' => sodium_crypto_secretbox(
                    json_encode($data),
                    $nonce,
                    $key
                ),
                'nonce' => $nonce
            ]
        );
    }

    public function generateKey(int $length = null): string
    {
        return random_bytes($length ?? SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    }

}
