<?php

namespace Fyre\Component\Image;

interface ImageHandlerInterface
{

    public function crop(string $input, ?string $output, int $width, int $height, int $x = 0, int $y = 0): bool;
    public function flip(string $image, ?string $output, string $axis = 'x');
    public function resize(string $input, ?string $output, int $width, ?int $height): bool;
    public function rotate(string $input, ?string $output, float $angle): bool;

}
