<?php

namespace Fyre\Component\Upload;

use
    Config\Services;

use const
    UPLOAD_ERR_OK;

use function
    array_key_exists,
    array_pop,
    basename,
    count,
    explode,
    file_exists,
    getimagesize,
    implode,
    in_array,
    is_dir,
    is_uploaded_file,
    is_writable,
    mime_content_type,
    mkdir,
    move_uploaded_file;

class Upload implements UploadInterface
{
    protected static $uploadErrors = [
        'limit',
        'form_limit',
        'partial',
        'file',
        'temp',
        'write',
        'extension'
    ];

    private $config;

    public function __construct(UploadConfig &$config)
    {
        $this->config = &$config;

		if ( ! is_dir($this->config->path)) {
            if ( ! mkdir($this->config->path, 0700, true)) {
                // path error
            }
        }

        Services::lang()->load('upload');

        Services::logger()->debug('Upload class loaded');
    }

    public function bulkUpload(string $name = 'file'): array
    {
        if ( ! array_key_exists($name, $_FILES)) {
            return $this->uploadError('file');
        }

        $files = [];

        foreach ($_FILES[$name] AS $key => &$value) {
            foreach ($value AS $file => &$data) {
                $files[$file][$key] = $data;
            }
        }

        $results = [];

        foreach ($files AS $file => &$data) {
            $_FILES[$name] =& $data;
            $results[] = $this->upload($name);
        }

        return $results;
    }

    public function upload(string $name = 'file'): array
    {
        if ( ! array_key_exists($name, $_FILES)) {
            return $this->uploadError('file');
        }

        $fileData =& $_FILES[$name];

        // check for upload error
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return $this->uploadError(static::$uploadErrors[$fileData['error'] - 1]);
        }

        $source = $fileData['tmp_name'];

        // check valid upload
        if ( ! is_uploaded_file($source)) {
            return $this->uploadError('file');
        }

        // check file size
        $fileSize = $fileData['size'] * 1024;
        if ($this->config->maxSize && $this->config->maxSize < $fileSize) {
            return $this->uploadError('max_size');
        }

        if ($this->config->minSize && $this->config->minSize > $fileSize) {
            return $this->uploadError('min_size');
        }

        // get mime type
        $mimeType = mime_content_type($source);
        $mimeArray = explode('/', $mimeType);
        $fileType = $mimeArray[0];
        $isImage = ($fileType === 'image');

        // check image size
        $imageWidth = false;
        $imageHeight = false;
        if ($isImage) {
            [$imageWidth, $imageHeight] = getimagesize($source);

            if ($this->config->maxWidth && $this->config->maxWidth < $imageWidth) {
                return $this->uploadError('image_size');
            }
 
            if ($this->config->minWidth && $this->config->minWidth > $imageWidth) {
                return $this->uploadError('image_size');
            }

            if ($this->config->maxHeight && $this->config->maxHeight < $imageHeight) {
                return $this->uploadError('image_size');
            }
 
            if ($this->config->minHeight && $this->config->minHeight > $imageHeight) {
                return $this->uploadError('image_size');
            }
        }

        $originalName = basename($fileData['name']);
        $fileName = $originalName;

        // get file extension
        $fileNameArray = explode('.', $fileName);
        $extension = false;
        if (count($fileNameArray) > 1) {
            $extension = array_pop($fileNameArray);
        }
    
        if ( ! $this->config->allowedTypes) {
            return $this->uploadError('types');
        }

        if ( ! in_array($extension, $this->config->allowedTypes)) {
            return $this->uploadError('type');
        }

        $fileBase = implode('.', $fileNameArray);

        $destination = $this->addPath($fileName);

        if ($this->config->encryptName) {
            do {
                $fileBase = str_random();
                $fileName = $this->addExtension($fileBase, $extension);
            } while (file_exists($destination));
        } else if ( ! $this->config->overwrite) {
            $counter = 1;

            while (file_exists($destination)) {
                if ($counter >= $this->config->maxFilenameIncrement) {
                    return $this->uploadError('exists');
                }

                $fileName = $this->addExtension($fileBase.$counter++, $extension);
                $destination = $this->addPath($fileName);
            }
        }
 
		if ( ! is_dir($this->config->path)) {
            return $this->uploadError('path');
        }

        if ( ! is_writable($this->config->path)) {
            return $this->uploadError('writable');
        }

        if ( ! move_uploaded_file($source, $destination)) {
            return $this->uploadError('move');
        }

        return [
            'extension' => $extension,
            'fileName' => $fileName,
            'filePath' => $this->config->path,
            'fileSize' => $fileSize,
            'fullPath' => $destination,
            'mimeType' => $mimeType,
            'imageWidth' => $imageWidth,
            'imageHeight' => $imageHeight,
            'isImage' => $isImage,
            'originalName' => $originalName,
            'rawName' => $fileBase
        ];
    }

    private function addExtension(string $fileBase, ?string $extension = null): string
    {
        if ( ! $extension) {
            return $fileBase;
        }

        return $fileBase.'.'.$extension;
    }

    private function addPath(string $fileName): string
    {
        return $this->config->path.'/'.$fileName;
    }

    private function uploadError(string $error_code): array
    {
        return [
            'error' => Services::lang()->get('upload.'.$error_code)
        ];
    }

}