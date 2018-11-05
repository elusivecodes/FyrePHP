<?php

namespace Frost;

use function
    explode,
    strlen,
    str_replace,

    str_after,
    str_before,
    str_begin,
    str_begins,
    str_camelize,
    str_contains,
    str_end,
    str_ends,
    str_random,
    str_replace_array,
    str_replace_first,
    str_replace_last,
    str_slug,
    str_title;

class Text
{
    private $text = '';

    public function __construct(string $text = '')
    {
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function after(string $string): self
    {
        return $this->pushText(str_after($this->text, $string));
    }

    public function before(string $string): self
    {
        return $this->pushText(str_before($this->text, $string));
    }

    public function begin(string $string): self
    {
        return $this->pushText(str_begin($this->text, $string));
    }

    public function begins(string $string): bool
    {
        return str_begins($this->text, $string);
    }

    public function camelize(): self
    {
        return $this->pushText(str_camelize($this->text));
    }

    public function chars(): array
    {
        return explode('', $this->text);
    }

    public function contains(string $match): bool
    {
        return str_contains($this->text, $match);
    }

    public function end(string $string): self
    {
        return $this->pushText(str_end($this->text, $string));
    }

    public function ends(string $string): bool
    {
        return str_ends($this->text, $string);
    }

    public function length(): int
    {
        return strlen($this->text);
    }

    public function pushText(string $text): self
    {
        $this->text = (string) $text;
        return $this;
    }

    public function random(int $length = 16): self
    {
        return $this->pushText(str_random($length));
    }

    public function replace(string $search, string $replace): self
    {
        return $this->pushText(str_replace($search, $replace, $this->text));
    }

    public function replaceArray(string $search, array $replace): self
    {
        return $this->pushText(str_replace_array($search, $replace, $this->text));
    }

    public function replaceFirst(string $search, string $replace): self
    {
        return $this->pushText(str_replace_first($search, $replace, $this->text));
    }

    public function replaceLast(string $search, string $replace): self
    {
        return $this->pushText(str_replace_last($search, $replace, $this->text));
    }

    public function slug(): self
    {
        return $this->pushText(str_slug($this->text));
    }

    public function title(): self
    {
        return $this->pushText(str_title($this->text));
    }

}
