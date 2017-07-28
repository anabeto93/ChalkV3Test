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
use App\Domain\Exception\Session\Import\IndexFileNotContainInZipException;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Size\Calculator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ContentImporter
{
    const IMAGE_PATH = '/content/course/%s/session/%s';

    /** @var FileStorageInterface */
    private $fileStorage;

    /** @var ContentParser */
    private $contentParser;

    /** @var ImageMover */
    private $imageMover;

    /** @var Calculator */
    private $calculator;

    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /**
     * @param FileStorageInterface       $fileStorage
     * @param ContentParser              $contentParser
     * @param ImageMover                 $imageMover
     * @param Calculator                 $calculator
     * @param SessionRepositoryInterface $sessionRepository
     */
    public function __construct(
        FileStorageInterface $fileStorage,
        ContentParser $contentParser,
        ImageMover $imageMover,
        Calculator $calculator,
        SessionRepositoryInterface $sessionRepository
    ) {
        $this->fileStorage = $fileStorage;
        $this->contentParser = $contentParser;
        $this->imageMover = $imageMover;
        $this->calculator = $calculator;
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @param Course             $course
     * @param string             $uuid
     * @param int                $rank
     * @param string             $title
     * @param UploadedFile       $uploadedFile
     * @param \DateTimeInterface $dateTime
     * @param Folder|null        $folder
     */
    public function import(
        Course $course,
        string $uuid,
        int $rank,
        string $title,
        UploadedFile $uploadedFile,
        \DateTimeInterface $dateTime,
        Folder $folder = null
    ) {
        $imagePath = sprintf(self::IMAGE_PATH, $course->getUuid(), $uuid);

        $pathToUpload = sprintf('/tmp/chalkboard_session_%s', uniqid());

        $zip = new \ZipArchive();
        $zip->open($uploadedFile->getPath() . '/' . $uploadedFile->getFilename());
        $zip->extractTo($pathToUpload);
        $zip->close();

        $indexFile = sprintf('%s/%s', $pathToUpload, 'index.html');
        $indexExist = $this->fileStorage->exists($indexFile);

        if (!$indexExist) {
            $this->fileStorage->remove($pathToUpload);

            throw new IndexFileNotContainInZipException();
        }

        $result = $this->contentParser->parse($indexFile, $imagePath);

        $session = new Session(
            $uuid,
            $rank,
            $title,
            $result->content,
            $course,
            $folder,
            $dateTime,
            $this->calculator->calculateSize(sprintf('%s%s%s', $uuid, $rank, $title))
        );

        $this->sessionRepository->add($session);

        $imagesSize = $this->imageMover->moveImages($session, $pathToUpload, $imagePath, $result, $dateTime);
        $session->setContentSize($imagesSize + $this->calculator->calculateSize($result->content));

        $this->sessionRepository->set($session);

        $this->fileStorage->remove($pathToUpload);
    }
}
