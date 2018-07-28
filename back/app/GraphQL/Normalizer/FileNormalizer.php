<?php


namespace App\GraphQL\Normalizer;


use App\File;

class FileNormalizer
{
    /**
     * Normalize a file's data
     *
     * @param File $file
     *
     * @return array
     */
    public function normalize(File $file): array {
        return [
            'url' => url('/').'/'.$file->path,
            'size' => $file->size,
        ];
    }
}