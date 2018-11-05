<?php

namespace Fyre\Engine\Loader;

use
    Fyre\Engine\Loader\Exceptions\LoaderException;

use const
    DIRECTORY_SEPERATOR,

    APP_PATH;

use function
    array_key_exists,
    array_push,
    array_unshift,
    file_exists,
    method_exists,
    rtrim,
    spl_autoload_register,
    str_replace,
    strrpost,
    substr,
    trim;

class Loader implements LoaderInterface
{
    private $prefixes = [];

    public function __construct(?string $config = null)
    {
        if ($config) {
            $path = APP_PATH.'Config/'.$config.'.php';

            if ( ! file_exists($path)) {
                LoaderException::configNotExists($config);
            }

            $loader = &$this;

            require APP_PATH.'Config/'.$config.'.php';
        }

        spl_autoload_register(
            [
                &$this,
                'loadClass'
            ]
        );
    }

    public function set(string $prefix, string $base_dir, bool $prepend = false): LoaderInterface
    {
        $prefix = trim(
            $prefix,
            '\\'
        ).'\\';

        if ( ! array_key_exists($prefix, $this->prefixes)) {
            $this->prefixes[$prefix] = [];
        }

        $base_dir = rtrim(
            $base_dir,
            DIRECTORY_SEPARATOR
        ).'/';

        if ($prepend) {
            array_unshift(
                $this->prefixes[$prefix],
                $base_dir
            );
        } else {
            array_push(
                $this->prefixes[$prefix],
                $base_dir
            );
        }

        return $this;
    }

    public function loadClass(string $class): bool
    {
        $prefix = $class;

        while (false !== $pos = strrpos(
            $prefix,
            '\\'
        )) {
            $prefix = substr(
                $class,
                0,
                $pos + 1
            );

            $relative_class = substr(
                $class,
                $pos + 1
            );

            if (array_key_exists($prefix, $this->prefixes)) {
                foreach ($this->prefixes[$prefix] AS $base_dir) {
                    $full_path = $base_dir.
                        str_replace(
                            '\\',
                            '/',
                            $relative_class
                        ).
                        '.php';

                    if (file_exists($full_path)) {
                        require $full_path;

                        return true;
                    }
                }
            }

            $prefix = rtrim(
                $prefix,
                '\\'
            );
        }

        return false;
    }
}
