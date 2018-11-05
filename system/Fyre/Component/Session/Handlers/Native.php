<?php

namespace Fyre\Component\Session\Handlers;

use
    SessionHandlerInterface,

    Config\Services,
    Fyre\Component\Session\SessionHandler;

use const
    LOCK_EX,
    LOCK_UN;

use function
    chmod,
    fclose,
    file_exists,
    filemtime,
    flock,
    fopen,
    fread,
    ftruncate,
    fwrite,
    glob,
    is_dir,
    is_resource,
    is_writable,
    mkdir,
    rewind,
    strlen,
    substr,
    time,
    unlink;

class Native extends SessionHandler implements SessionHandlerInterface {
    private $filePath;
    private $fileHandle;
    private $fileNew;

    public function open($save_path, $session_name)
    {
        if ( ! is_dir($save_path)) {
            if ( ! mkdir($save_path, 0700)) {
                Services::logger()->error('Unable to create session folder.');
            }
        } else if ( ! is_writable($save_path)) {
            Services::logger()->error('Session folder is not writable.');
        }

        $this->filePath = $save_path.'/'.$session_name;

        return true;
    }

    public function close()
    {
        if (is_resource($this->fileHandle)) {
            flock($this->fileHandle, LOCK_UN);
            fclose($this->fileHandle);

            $this->fileHandle = $this->fileNew = null;
        }

        return true;
    }

    public function read($key)
    {
        $file = $this->filePath.$key;

        $this->fileNew = ! file_exists($file);

        $this->fileHandle = fopen($file, 'c+');
        if ( ! $this->fileHandle) {
            Services::logger()->error('Unable to create session file.');
            return false;
        }

        if ( ! flock($this->fileHandle, LOCK_EX)) {
            Services::logger()->error('Unable to get lock on session file.');
            fclose($this->fileHandle);
            $this->fileHandle = null;
            return false;
        }

        if ($this->fileNew) {
            chmod($file, 0600);
            return '';
        }

        $session_data = '';
        while ($buffer = fread($this->fileHandle, 8192)) {
            $session_data .= $buffer;
        }

        return $session_data;
    }

    public function write($key, $val)
    {
        if ( ! $this->fileNew) {
            ftruncate($this->fileHandle, 0);
            rewind($this->fileHandle);
        }
    
        $written = 0;
        for ($bytes = 0; $bytes < strlen($val) && $written !== FALSE; $bytes += $written) {
            $written = fwrite($this->fileHandle, substr($val, $bytes));
        }
    
        return true;
    }

    public function destroy($key)
    {
        if (file_exists($this->filePath.$key)) {
            return unlink($this->filePath.$key);
        }

        return true;
    }

    public function gc($lifetime)
    {
        foreach (glob($this->filePath.'.*') AS $file) {
            if (file_exists($file) && filemtime($file) + $lifetime < time()) {
                unlink($file);
            }
        }

        return true;
    }

}
