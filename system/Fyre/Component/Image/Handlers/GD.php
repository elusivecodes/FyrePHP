<?php

namespace Fyre\Component\Image\Handlers;

use
    Fyre\Component\Image\ImageConfig,
    Fyre\Component\Image\ImageHandler,
    Fyre\Component\Image\ImageHandlerInterface;

use const
    IMG_FLIP_BOTH,
    IMG_FLIP_HORIZONTAL,
    IMG_FLIP_VERTICAL,
    PATHINFO_EXTENSION;

use function
    exif_imagetype,
    image_type_to_extension,
    imagebmp,
    imagecreatefrombmp,
    imagecreatefromgif,
    imagecreatefromjpeg,
    imagecreatefrompng,
    imagecreatefromwebp,
    imagecrop,
    imagedestroy,
    imageflip,
    imagegif,
    imagejpeg,
    imagepng,
    imagescale,
    imagerotate,
    imagewebp,
    pathinfo;

class GD extends ImageHandler implements ImageHandlerInterface
{

    public function crop(string $input, ?string $output, int $width, int $height, int $x = 0, int $y = 0): bool
    {
        $image = $this->createFromImage($input);

        if ( ! $image) {
            return false;
        }

        $cropped = imagecrop(
            $image,
            [
                'x' => $x,
                'y' => $y,
                'width' => $width,
                'height' => $height
            ]
        );

        imagedestroy($image);

        if ( ! $cropped) {
            return false;
        }

        $result = $this->createImage($cropped, $output ?? $input);

        imagedestroy($cropped);

        return $result;
    }

    public function flip(string $input, ?string $output, string $axis = 'x'): bool
    {
        $image = $this->createFromImage($input);

        if ( ! $image) {
            return false;
        }

        switch ($axis) {
            case 'x':
                $mode = IMG_FLIP_HORIZONTAL;
                break;
            case 'y':
                $mode = IMG_FLIP_VERTICAL;
                break;
            default:
                $mode = IMG_FLIP_BOTH;
                break;
        }

        $flipped = imageflip($image, $mode);

        imagedestroy($image);

        if ( ! $flipped) {
            return false;
        }

        $result = $this->createImage($flipped, $output ?? $input);

        imagedestroy($flipped);

        return $result;
    }

    public function resize(string $input, ?string $output, int $width, ?int $height = null): bool
    {
        $image = $this->createFromImage($input);

        if ( ! $image) {
            return false;
        }

        $resized = imagescale($image, $width, $height ?? -1);

        imagedestroy($image);

        if ( ! $resized) {
            return false;
        }

        $result = $this->createImage($resized, $output ?? $input);

        imagedestroy($resized);

        return $result;
    }

    public function rotate(string $input, ?string $output, float $angle): bool
    {
        $image = $this->createFromImage($input);

        if ( ! $image) {
            return false;
        }

        $rotated = imagerotate($image, $angle);

        imagedestroy($image);

        if ( ! $rotated) {
            return false;
        }

        $result = $this->createImage($resized, $output ?? $input);

        imagedestroy($rotated);

        return $result;
    }

    private function createFromImage(string $source)
    {
        $type = exif_imagetype($source);

        if ( ! $type) {
            return null;
        }

        $ext = image_type_to_extension($type);

        switch ($ext) {
            case '.bmp':
                return imagecreatefrombmp($source);
                break;
            case '.gif':
                return imagecreatefromgif($source);
                break;
            case '.jpeg':
                return imagecreatefromjpeg($source);
                break;
            case '.png':
                return imagecreatefrompng($source);
                break;
            case '.webp':
                return imagecreatefromwebp($source);
                break;
        }

        return null;
    }

    private function createImage(&$image, string $output): bool
    {
        $ext = pathinfo($output, PATHINFO_EXTENSION);

        switch ($ext) {
            case 'bmp':
                return imagebmp($image, $output);
                break;
            case 'gif':
                return imagegif($image, $output);
                break;
            case 'jpeg':
            case 'jpg':
                return imagejpeg($image, $output);
                break;
            case 'png':
                return imagepng($image, $output);
                break;
            case 'webp':
                return imagewebp($image, $output);
                break;
        }

        return false;
    }

}
