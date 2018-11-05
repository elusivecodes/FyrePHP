<?php

namespace Fyre\Component\Upload;

interface UploadInterface
{

    public function upload(string $name = 'file'): array;

}
