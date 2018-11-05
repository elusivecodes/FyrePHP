<?php

namespace Fyre\Engine\Lang;

interface LangInterface
{

    public function get(string $key): ?string;
    public function load(string $file): void;
    public function set(string $key, string $text): void;

}
