<?php

namespace Frost;

class TextImmutable extends Text
{

    public function pushText(string $text): self
    {
        return new TextImmutable($text);
    }

}
