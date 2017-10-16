<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Session;

use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Session\Import\FilesImportRemover;

class DeleteHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var string */
    private $uploadDir;

    /** @var FilesImportRemover */
    private $filesImportRemover;

    /**
     * @param FilesImportRemover         $filesImportRemover
     * @param SessionRepositoryInterface $sessionRepository
     * @param string                     $uploadDir
     */
    public function __construct(
        FilesImportRemover $filesImportRemover,
        SessionRepositoryInterface $sessionRepository,
        string $uploadDir
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->uploadDir = $uploadDir;
        $this->filesImportRemover = $filesImportRemover;
    }

    /**
     * @param Delete $command
     */
    public function handle(Delete $command)
    {
        $this->filesImportRemover->removeFiles($command->session, $this->uploadDir);

        $this->sessionRepository->remove($command->session);
    }
}
