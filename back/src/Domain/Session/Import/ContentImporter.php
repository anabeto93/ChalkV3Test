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

    /** @var string */
    private $pathToStoreUpload;

    /** @var FilesImportRemover */
    private $filesImportRemover;

    /**
     * @param FilesImportRemover         $filesImportRemover
     * @param FileStorageInterface       $fileStorage
     * @param ContentParser              $contentParser
     * @param ImageMover                 $imageMover
     * @param Calculator                 $calculator
     * @param SessionRepositoryInterface $sessionRepository
     * @param string                     $pathToStoreUpload
     */
    public function __construct(
        FilesImportRemover $filesImportRemover,
        FileStorageInterface $fileStorage,
        ContentParser $contentParser,
        ImageMover $imageMover,
        Calculator $calculator,
        SessionRepositoryInterface $sessionRepository,
        string $pathToStoreUpload
    ) {
        $this->fileStorage = $fileStorage;
        $this->contentParser = $contentParser;
        $this->imageMover = $imageMover;
        $this->calculator = $calculator;
        $this->sessionRepository = $sessionRepository;
        $this->pathToStoreUpload = $pathToStoreUpload;
        $this->filesImportRemover = $filesImportRemover;
    }

    /**
     * @param Course             $course
     * @param string             $uuid
     * @param int                $rank
     * @param string             $title
     * @param UploadedFile       $uploadedFile
     * @param \DateTimeInterface $dateTime
     * @param Folder|null        $folder
     * @param bool               $needValidation
     * @param bool               $enabled
     */
    public function importNewSession(
        Course $course,
        string $uuid,
        int $rank,
        string $title,
        UploadedFile $uploadedFile,
        \DateTimeInterface $dateTime,
        Folder $folder = null,
        bool $needValidation,
        bool $enabled
    ) {
        $imagePath = $this->getImagePath($course->getUuid(), $uuid);
        $pathToUpload = $this->getPathToUpload($uuid);

        $contentParsedView = $this->extractArchive($uploadedFile, $imagePath, $pathToUpload);

        $session = new Session(
            $uuid,
            $rank,
            $title,
            $contentParsedView->content,
            $course,
            $folder,
            $needValidation,
            $enabled,
            $dateTime,
            $this->calculator->calculateSize(sprintf('%s%s%s', $uuid, $rank, $title))
        );

        $this->sessionRepository->add($session);

        $this->moveImages($session, $contentParsedView, $pathToUpload, $imagePath, $dateTime);
    }

    /**
     * @param Session            $session
     * @param UploadedFile       $uploadedFile
     * @param \DateTimeInterface $dateTime
     */
    public function importUpdateSession(
        Session $session,
        UploadedFile $uploadedFile,
        \DateTimeInterface $dateTime
    ) {
        $imagePath = $this->getImagePath($session->getCourse()->getUuid(), $session->getUuid());
        $pathToUpload = $this->getPathToUpload($session->getUuid());

        $this->filesImportRemover->removeFiles($session, $this->imageMover->getUploadDir());

        $contentParsedView = $this->extractArchive($uploadedFile, $imagePath, $pathToUpload);

        $session->updateContent($contentParsedView->content, $dateTime);

        // The set is done in the moveImages method
        $this->moveImages($session, $contentParsedView, $pathToUpload, $imagePath, $dateTime);
    }

    /**
     * @param string $courseUuid
     * @param string $sessionUuid
     *
     * @return string
     */
    private function getImagePath(string $courseUuid, string $sessionUuid): string
    {
        return sprintf(self::IMAGE_PATH, $courseUuid, $sessionUuid);
    }

    /**
     * @param string $sessionUuid
     *
     * @return string
     */
    private function getPathToUpload(string $sessionUuid): string
    {
        return sprintf('%s/chalkboard_session_%s', $this->pathToStoreUpload, $sessionUuid);
    }

    /**
     * @param Session            $session
     * @param ContentParsedView  $contentParsedView
     * @param string             $pathToUpload
     * @param string             $imagePath
     * @param \DateTimeInterface $dateTime
     */
    private function moveImages(
        Session $session,
        ContentParsedView $contentParsedView,
        string $pathToUpload,
        string $imagePath,
        \DateTimeInterface $dateTime
    ) {
        $imagesSize = $this->imageMover->moveImages(
            $session,
            $pathToUpload,
            $imagePath,
            $contentParsedView,
            $dateTime
        );
        $session->setContentSize($imagesSize + $this->calculator->calculateSize($contentParsedView->content));

        $this->sessionRepository->set($session);

        $this->fileStorage->remove($pathToUpload);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string       $imagePath
     * @param string       $pathToUpload
     *
     * @return ContentParsedView
     */
    private function extractArchive(
        UploadedFile $uploadedFile,
        string $imagePath,
        string $pathToUpload
    ): ContentParsedView {
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

        return $this->contentParser->parse($indexFile, $imagePath);
    }
}
