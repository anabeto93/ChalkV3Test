<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Repository\Session\FileRepositoryInterface;
use App\Domain\Repository\SessionRepositoryInterface;

class DeleteHandler
{
    /** @var FileRepositoryInterface */
    private $fileRepository;

    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var FileStorageInterface */
    private $fileStorage;

    /** @var string */
    private $uploadDir;

    /**
     * @param FileRepositoryInterface    $fileRepository
     * @param SessionRepositoryInterface $sessionRepository
     * @param FileStorageInterface       $fileStorage
     * @param string                     $uploadDir
     */
    public function __construct(
        FileRepositoryInterface $fileRepository,
        SessionRepositoryInterface $sessionRepository,
        FileStorageInterface $fileStorage,
        string $uploadDir
    ) {
        $this->fileRepository = $fileRepository;
        $this->sessionRepository = $sessionRepository;
        $this->fileStorage = $fileStorage;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command)
    {
        foreach ($command->session->getFiles() as $file) {
            $this->fileStorage->remove(sprintf('%s%s', $this->uploadDir, $file->getPath()));
            $this->fileRepository->remove($file);
        }

        $this->sessionRepository->remove($command->session);
    }
}
