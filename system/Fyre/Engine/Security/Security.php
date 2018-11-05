<?php

namespace Fyre\Engine\Security;

use
    Config\Services;

use const
    FILTER_FLAG_ENCODE_LOW,
    FILTER_SANITIZE_SPECIAL_CHARS;

class Security implements SecurityInterface
{
    private $config;

    use
        Csrf,
        Password;

    public function __construct(SecurityConfig &$config)
    {
        $this->config = &$config;

        if ($this->config->csrfProtection) {
            $this->csrfHash();
            $this->csrfVerify();
        }

        Services::logger()->debug('Security class loaded');
    }

    public function sanitize(string $value): string
    {
        return filter_var(
            $value,
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
    }

}
