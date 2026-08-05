<?php

declare(strict_types=1);

namespace App\Core;

final class ImageOptimizer
{
    public static function optimize(string $path): void
    {
        if (! extension_loaded('gd')) {
            return;
        }

        $info = getimagesize($path);
        if ($info === false) {
            return;
        }

        [$width, $height, $type] = $info;
        $maxW = 1600;
        if ($width <= $maxW) {
            return;
        }

        $ratio = $height / $width;
        $newW = $maxW;
        $newH = (int) round($newW * $ratio);

        $src = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($path);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($path);
                break;
            default:
                return;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        if ($type === IMAGETYPE_JPEG) {
            imagejpeg($dst, $path, 78);
        } elseif ($type === IMAGETYPE_PNG) {
            imagepng($dst, $path, 6);
        }

        imagedestroy($src);
        imagedestroy($dst);
    }
}
