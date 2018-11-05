<?php

namespace Fyre\Component\Session;

use
    Config\Services;

use const
    PHP_SESSION_ACTIVE;

use function
    array_key_exists,
    ini_get,
    ini_set,
    property_exists,
    register_shutdown_function,
    rtrim,
    session_destroy,
    session_id,
    session_regenerate_id,
    session_set_save_handler,
    session_start,
    session_status,
    session_write_close,
    setcookie,
    time,

    config;

class Session implements SessionInterface
{
    protected $handler;
    protected $config;

    private $flashData = [];
    private $tempData = [];

    public function __construct(SessionConfig &$config)
    {
        if ( ! $config->expires) {
            $config->expires = ini_get('session.gc_maxlifetime');
        }

        if (property_exists($config, 'cookieLifetime')) {
            $config->cookieLifetime = (int) $config->cookieLifetime;
        } else {
            $config->cookieLifetime = $config->temporary ?
                0 :
                $config->expires;
        }

        if ($config->cookie) {
            ini_set('session.name', $config->cookie);
        } else {
            $config->cookie = ini_get('session.name');
        }

        property_exists($config, 'cookieDomain') || $config->cookieDomain = config('cookieDomain');
        property_exists($config, 'cookiePath') || $config->cookiePath = config('cookiePath');
        property_exists($config, 'cookieSecure') || $config->cookieSecure = !! config('cookieSecure');

        ini_set('session.gc_maxlifetime', $config->expires);
        ini_set('session.cookie_lifetime', $config->cookieLifetime);
        ini_set('session.cookie_domain', $config->cookieDomain);
        ini_set('session.cookie_path', $config->cookiePath);
        ini_set('session.cookie_secure', $config->cookieSecure);
        ini_set('session.save_path', $config->path);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.lazy_write', 1);
        ini_set('session.sid_length ', 48);
        ini_set('session.sid_bits_per_character', 6);

        $className = $config->handler;

        $this->handler = new $className($config);
        $this->config = &$config;

        session_set_save_handler($this->handler);
        register_shutdown_function('session_write_close');
        session_start();

        $this->checkRefresh();

        if (array_key_exists('_flashData', $_SESSION)) {
            foreach ($_SESSION['_flashData'] AS $key => $value) {
                $this->flashData[$key] = $value;
            }
        }

        $_SESSION['_flashData'] = [];

        if (array_key_exists('_tempData', $_SESSION)) {
            $time = time();
            foreach ($_SESSION['_tempData'] AS $key => $expires) {
                if ($expires <= $time) {
                    unset($_SESSION[$key]);
                    unset($_SESSION['_tempData'][$key]);
                }
            }
        } else {
            $_SESSION['_tempData'] = [];
        }

        Services::logger()->debug('Session class loaded');
    }

    public function close(): bool
    {
        return session_write_close();
    }

    public function destroy(): bool
    {
        return session_destroy() && $this->destroyCookie();
    }

    public function destroyCookie(): bool
    {
        return setcookie(
            $this->config->cookie,
            '',
            0,
            $this->config->cookiePath,
            $this->config->cookieDomain,
            $this->config->cookieSecure,
            1
        );
    }

    public function get(string $key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }

        if ($key === 'session_id') {
            return session_id();
        }

        return null;
    }

    public function has(string $key)
    {
        if ($key === 'session_id') {
            return session_status() === PHP_SESSION_ACTIVE;
        }

        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function setFlashdata(string $key, $value)
    {
        $_SESSION['_flashData'][$key] = $value;
        return $this;
    }

    public function setTempData(string $key, $value, int $expires = 300)
    {
        $_SESSION['_tempData'][$key] = time() + $expires;
        return $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return $this->has($key);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    private function checkRefresh(): void
    {
        if ( ! isset($_SESSION['_refreshed'])) {
            $_SESSION['_refreshed'] = time();
        } else if ($_SESSION['_refreshed'] < time() - $this->config->refresh) {
            $_SESSION['_refreshed'] = time();
            session_regenerate_id($this->config->cleanup);
        }
    }

}
