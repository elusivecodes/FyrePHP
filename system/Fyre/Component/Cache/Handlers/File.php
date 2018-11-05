<?php

namespace Fyre\Component\Cache\Handlers;

use
    Config\Services,
    Fyre\Component\Cache\CacheConfig,
    Fyre\Component\Cache\CacheHandler,
    Fyre\Component\Cache\CacheHandlerInterface,
    Fyre\Component\Cache\Exceptions\FileException;

use const
    LOCK_EX;

use function
    array_pop,
    config,
    explode,
    file_exists,
    file_get_contents,
    file_put_contents,
    implode,
    is_dir,
    is_writable,
    json_decode,
    json_encode,
    md5,
    mkdir,
    property_exists,
    time,
    unlink,

    dir_delete,
    dir_size;

class File extends CacheHandler implements CacheHandlerInterface
{

    public function __construct(CacheConfig &$config)
    {
        parent::__construct($config);

        if ( ! property_exists($this->config, 'path')) {
            FileException::noPath();
        }
    }

    public function decrement(string $key, int $amount = 1): int
    {
        $value = (int) $this->get($key);

        $value -= $amount;

        $this->save($key, $value + $amount);

        return $value;
    }

    public function delete(string $key = ''): bool
    {
		return dir_delete(
            $this->folderPath($key)
        );
	}

    public function forget(string $key): bool
    {
        $filePath = $this->filePath($key);

        if (file_exists($filePath) &&
            is_writable($filePath) &&
            ! is_dir($filePath)) {
            unlink($filePath);
            return true;
        }

        return false;
    }

    public function get(string $key)
    {
        $filePath = $this->filePath($key);

		if ( ! file_exists($filePath)) {
            return null;
        }

		$data = file_get_contents($filePath);
		if ( ! $data) {
			Services::logger()->error('Cache not opened: '.$filePath);
			return null;
		}

		$data = json_decode($data, true);

		// Has the file expired? If so we'll delete it.
		if ($data['expire'] !== 0 && time() >= $data['expire'] && is_writable($filePath)) {
			unlink($filePath);
			return null;
		}

		return $data['data'];
	}

    public function has(string $key): bool
    {
		return (bool) $this->get($key);
    }

    public function increment(string $key, int $amount = 1): int
    {
        $value = (int) $this->get($key);

        $value += $amount;

        $this->save($key, $value + $amount);

        return $value;
    }

    public function save(string $key, $data, int $expire = 0): bool
    {
        $filePath = $this->filePath($key);

		if ( ! write_file(
			$filePath,
			json_encode(
				[
					'created' => time(),
					'expire' =>  $expire !== 0 ? time() + $expire * 60 : $expire,
					'data' => $data
                ]
            ),
            LOCK_EX
		)) {
			Services::logger()->error('Unable to write cache file: '.$filePath);
			return false;
		}

		return true;
    }

    public function size(string $key = ''): ?int
    {
		return dir_size(
            $this->folderPath($key)
        );
    }

    private function folderPath(string $path): string
    {
        return $path ?
            str_end($this->config->path, '/').$path :
            $this->config->path;
    }

    private function filePath(string $path): string
    {
		$path_array = explode('/', $path);
		$path_array[] = md5(array_pop($path_array));
		return str_end($this->config->path, '/').implode('/', $path_array);
	}

}
