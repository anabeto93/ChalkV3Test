<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Adapter;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Exception\FileStorage\FileDoesNotExistException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorage implements FileStorageInterface
{
    /** @var Filesystem */
    private $fileSystem;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param Filesystem         $fileSystem
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(Filesystem $fileSystem, \DateTimeInterface $dateTime)
    {
        $this->fileSystem = $fileSystem;
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function copy(string $pathFrom, string $pathTo)
    {
        $this->fileSystem->copy($pathFrom, $pathTo, true);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $path): bool
    {
        return $this->fileSystem->exists($path);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $pathToRemove)
    {
        $this->fileSystem->remove($pathToRemove);
    }

    /**
     * {@inheritdoc}
     *
     * @throws FileDoesNotExistException
     */
    public function size(string $path): int
    {
        $size = filesize($path);

        if ($size === false) {
            throw new FileDoesNotExistException(
                sprintf('The file %s does not exist and the size could not be calculated', $path)
            );
        }

        return $size;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function upload(UploadedFile $file, string $directoryPath = null)
    {
        $content = file_get_contents($file);

        $filePath = sprintf(
            '%s/%s_%s.%s',
            $this->getAnnualizedPath(),
            uniqid(),
            uniqid(),
            $file->getClientOriginalExtension()
        );

        $this->fileSystem->dumpFile(sprintf('%s/%s', $directoryPath, $filePath), $content);

        return $filePath;
    }

    /**
     * @param string|null $extraDirInPath should be a string ending with a "/"
     *
     * @return string
     */
    private function getAnnualizedPath($extraDirInPath = null)
    {
        return sprintf('/%s%s/%s', $extraDirInPath, $this->dateTime->format('Y'), $this->dateTime->format('m'));
    }
}
