<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Import;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Charset\Charset;
use App\Domain\Model\Upload\File;
use App\Domain\Repository\Upload\FileRepositoryInterface;

class ImportHandler
{
    /** @var FileStorageInterface */
    private $fileStorage;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var string */
    private $importDir;

    /** @var FileRepositoryInterface */
    private $fileRepository;

    /**
     * @param FileRepositoryInterface $fileRepository
     * @param FileStorageInterface    $fileStorage
     * @param string                  $importDir
     * @param \DateTimeInterface      $dateTime
     */
    public function __construct(
        FileRepositoryInterface $fileRepository,
        FileStorageInterface $fileStorage,
        string $importDir,
        \DateTimeInterface $dateTime
    ) {
        $this->fileStorage = $fileStorage;
        $this->dateTime = $dateTime;
        $this->importDir = $importDir;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Import $command
     *
     * @return File
     */
    public function handle(Import $command): File
    {
        $this->fileStorage->changeEncoding($command->file, $command->charset, Charset::UTF_8);

        $filePath = $this
            ->fileStorage
            ->upload(
                $command->file,
                $this->importDir
            );

        $file = new File($filePath, $this->dateTime);
        $this->fileRepository->add($file);

        return $file;
    }
}
