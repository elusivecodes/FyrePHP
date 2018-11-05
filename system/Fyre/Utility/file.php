<?php

if ( ! function_exists('dir_delete')) {
    function dir_delete(string $path): bool
    {
        return $path &&
            is_dir($path) ?
                array_map(
                    function($child) {
                        if (is_dir($child['path'])) {
                            dir_delete($child['path']);
                        } else {
                            unlink($child['path']);
                        }
                    },
                    dir_files($path)
                ) &&
                    rmdir($path) :
                false;
    }
}

if ( ! function_exists('dir_size')) {
    function dir_size(string $path): ?int
    {
        return $path &&
            is_dir($path) ?
                array_reduce(
                    dir_files($path),
                    function($a, $b) {
                        return $a +
                            is_dir($b['path']) ?
                                dir_size($b['path']) :
                                $b['size'];
                    },
                    0
                ) :
                null;
    }
}

if ( ! function_exists('dir_files')) {
    function dir_files(string $path): ?array
    {
        return $path &&
            is_dir($path) ?
                array_map(
                    function($value) use ($path) {
                        $fullPath = $path.'/'.$value;

                        return [
                            'path' => $fullPath,
                            'size' => filesize($fullPath)
                        ];
                    },
                    array_diff(
                        scandir($path),
                        ['..', '.']
                    )
                ) :
                null;
    }
}

if ( ! function_exists('write_file')) {
    function write_file(string $path, string $data, int $flags = 0, ?int $chmod = null)
    {
        $dir = dirname($path);

        return (
                is_dir($dir) ||
                mkdir($dir, 0700, true)
            ) &&
            is_writable($dir) &&
            file_put_contents($path, $data, $flags) &&
            (
                ! $chmod ||
                chmod($path, $chmod)
            );
    }
}
