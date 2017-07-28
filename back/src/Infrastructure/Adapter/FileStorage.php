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
use Symfony\Component\Filesystem\Filesystem;

class FileStorage implements FileStorageInterface
{
    /**
     * @var Filesystem
     */
    public $fileSystem;

    /**
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
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
}
