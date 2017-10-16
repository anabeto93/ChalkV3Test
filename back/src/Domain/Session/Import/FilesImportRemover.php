<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\Import;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\FileRepositoryInterface;

class FilesImportRemover
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
     * @param Session $session
     * @param string  $uploadDir
     */
    public function removeFiles(Session $session, string $uploadDir)
    {
        foreach ($session->getFiles() as $file) {
            $this->fileStorage->remove(sprintf('%s%s', $uploadDir, $file->getPath()));
            $this->fileRepository->remove($file);
        }

        $session->setFiles([]);
    }
}
