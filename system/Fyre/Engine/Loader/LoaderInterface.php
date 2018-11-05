<?php

namespace Fyre\Engine\Loader;

interface LoaderInterface
{

    public function set(string $prefix, string $base_dir, bool $prepend = false): LoaderInterface;
    public function loadClass(string $class): bool;

}
