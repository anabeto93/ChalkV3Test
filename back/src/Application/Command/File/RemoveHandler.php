<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\File;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Repository\Upload\FileRepositoryInterface;

class RemoveHandler
{
    /** @var FileStorageInterface */
    private $fileStorage;

    /** @var FileRepositoryInterface */
    private $fileRepository;

    /**
     * @param FileStorageInterface    $fileStorage
     * @param FileRepositoryInterface $fileRepository
     */
    public function __construct(FileStorageInterface $fileStorage, FileRepositoryInterface $fileRepository)
    {
        $this->fileStorage = $fileStorage;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Remove $command
     */
    public function handle(Remove $command)
    {
        if ($this->fileStorage->exists(sprintf('%s%s', $command->dir, $command->file->getPath()))) {
            $this->fileStorage->remove(sprintf('%s%s', $command->dir, $command->file->getPath()));
        }

        $this->fileRepository->remove($command->file);
    }
}
