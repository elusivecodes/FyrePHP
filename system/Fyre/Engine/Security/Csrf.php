<?php

namespace Fyre\Engine\Security;

use function
    array_key_exists,
    bin2hex,
    ctype_xdigit,
    hash_equals,
    is_string,
    random_bytes,
    setcookie,
    strtoupper,
    time,

    config;

trait Csrf
{
    private $csrfHash = null;

    private function csrfCookie(): bool
    {
        return setcookie(
            $this->config->csrfCookie,
            $this->csrfHash,
            time() + $this->config->csrfExpires,
            config('cookiePath'),
            config('cookieDomain'),
            config('cookieSecure')
        );
    }

    public function csrfHash(): string
    {
        if ($this->csrfHash === null) {
            if (array_key_exists($this->config->csrfCookie, $_COOKIE)
                && is_string($_COOKIE[$this->config->csrfCookie])
                && ctype_xdigit($_COOKIE[$this->config->csrfCookie])) {
                $this->csrfHash = $_COOKIE[$this->config->csrfCookie];
            } else {
                $this->csrfHash = bin2hex(random_bytes(16));
            }
        }

        return $this->csrfHash;
    }

    public function csrfToken(): string
    {
        return $this->config->csrfToken;
    }

    private function csrfVerify(): bool
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            return $this->csrfCookie();
        }

        $valid = array_key_exists($this->config->csrfToken, $_POST) &&
            array_key_exists($this->config->csrfCookie, $_COOKIE) &&
            hash_equals($_POST[$this->config->csrfToken], $_COOKIE[$this->config->csrfCookie]);

        unset($_POST[$this->config->csrfToken]);

        if ($this->config->csrfRegenerate) {
            unset($_COOKIE[$this->config->csrfCookie]);
            $this->csrfHash = null;
            $this->csrfHash();
        }

        $this->csrfCookie();

        if ( ! $valid) {
            // error
            echo 'csrf error';
            exit;
        }

        return true;
    }

}
