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
use App\Domain\Exception\Session\Import\ImageFileNotPresentException;
use App\Domain\Model\Session;
use App\Domain\Model\Session\File;
use App\Domain\Repository\Session\FileRepositoryInterface;

class ImageMover
{
    /** @var FileStorageInterface */
    private $fileStorage;

    /** @var FileRepositoryInterface */
    private $fileRepository;

    /** @var string */
    private $uploadDir;

    /**
     * @param FileStorageInterface    $fileStorage
     * @param FileRepositoryInterface $fileRepository
     * @param string                  $uploadDir
     */
    public function __construct(
        FileStorageInterface $fileStorage,
        FileRepositoryInterface $fileRepository,
        string $uploadDir
    ) {
        $this->fileStorage = $fileStorage;
        $this->fileRepository = $fileRepository;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @param Session            $session
     * @param string             $imagesLocation
     * @param string             $finalLocation
     * @param ContentParsedView  $contentParsedView
     * @param \DateTimeInterface $uploadedDate
     *
     * @return int
     */
    public function moveImages(
        Session $session,
        string $imagesLocation,
        string $finalLocation,
        ContentParsedView $contentParsedView,
        \DateTimeInterface $uploadedDate
    ): int {
        $filesSize = 0;

        foreach ($contentParsedView->imagesFound as $image) {
            $currentPath = sprintf('%s/%s', $imagesLocation, $image);

            if (!$this->fileStorage->exists($currentPath)) {
                throw new ImageFileNotPresentException($image);
            }

            $finalPath = sprintf('%s/%s', $finalLocation, $image);
            $absolutePath = sprintf('%s%s', $this->uploadDir, $finalPath);

            $this->fileStorage->copy($currentPath, $absolutePath);

            $imageSize = filesize($absolutePath);
            $filesSize += $imageSize;

            $sessionFile = new File(
                $session,
                $finalPath,
                $imageSize,
                $uploadedDate
            );

            $this->fileRepository->add($sessionFile);
        }

        return $filesSize;
    }
}
