<?php

namespace Fyre\Component\Encryption\Handlers;

use
    Fyre\Component\Encryption\EncryptionConfig,
    Fyre\Component\Encryption\EncryptionHandler,
    Fyre\Component\Encryption\EncryptionHandlerInterface,
    Fyre\Component\Encryption\Exception\OpenSSLException;

use function
    json_decode,
    json_encode,
    openssl_cipher_iv_length,
    openssl_decrypt,
    openssl_encrypt,
    openssl_random_pseudo_bytes,
    serialize,
    unserialize;

use const
    OPENSSL_KEYTYPE_RSA,
    OPENSSL_RAW_DATA;

class OpenSSL extends EncryptionHandler implements EncryptionHandlerInterface
{

    public function __construct(EncryptionConfig &$config)
    {
        parent::__construct($config);

        if ( ! property_exists($this->config, 'encryptionMode')) {
            OpenSSLException::noMode();
        }
    }

    public function decrypt(string $data, string $key)
    {
        $data = unserialize($data);

        return json_decode(
            openssl_decrypt(
                $data['data'],
                $this->config->encryptionMode,
                $key,
                OPENSSL_RAW_DATA,
                $data['iv']
            )
        );
    }

    public function encrypt($data, string $key): string
    {
        $iv = $this->generateKey(
            openssl_cipher_iv_length($this->config->encryptionMode)
        );

        return serialize(
            [
                'data' => openssl_encrypt(
                    json_encode($data),
                    $this->config->encryptionMode,
                    $key,
                    OPENSSL_RAW_DATA,
                    $iv
                ),
                'iv' => $iv
            ]
        );
    }

    public function generateKey(int $length = null): string
    {
        $key = openssl_random_pseudo_bytes($length ?? 24, $secure);

        return $secure ?
            $key :
            $this->generateKey($length);
    }

}
