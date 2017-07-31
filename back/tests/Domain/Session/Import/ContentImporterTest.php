<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Session\Import;

use App\Application\Adapter\FileStorageInterface;
use App\Domain\Exception\Session\Import\IndexFileNotContainInZipException;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Session\Import\ContentImporter;
use App\Domain\Session\Import\ContentParsedView;
use App\Domain\Session\Import\ContentParser;
use App\Domain\Session\Import\ImageMover;
use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ContentImporterTest extends TestCase
{
    /** @var ObjectProphecy */
    public $fileStorage;

    /** @var ObjectProphecy */
    public $contentParser;

    /** @var ObjectProphecy */
    public $imageMover;

    /** @var ObjectProphecy */
    public $calculator;

    /** @var ObjectProphecy */
    public $sessionRepository;

    public function setUp()
    {
        $this->fileStorage = $this->prophesize(FileStorageInterface::class);
        $this->contentParser = $this->prophesize(ContentParser::class);
        $this->imageMover = $this->prophesize(ImageMover::class);
        $this->calculator = $this->prophesize(Calculator::class);
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
    }

    /**
     * To call at the end of test to remove folder created in tmp
     */
    private function cleanAfterTest()
    {
        unlink('/tmp/chalkboard_session_123-123-123/index.html');
        rmdir('/tmp/chalkboard_session_123-123-123');
    }

    public function testImportNoIndex()
    {
        $this->setExpectedException(IndexFileNotContainInZipException::class);

        // Context
        $course = $this->prophesize(Course::class);
        $course->getUuid()->willReturn('321-321-321');
        $uuid = '123-123-123';
        $rank = 3;
        $title = 'title';
        $folder = null;
        $dateTime = new \Datetime();
        $uploadedFile = new UploadedFile(__DIR__ . '/content.zip', 'application/zip');

        // expected
        $this->fileStorage->exists('/tmp/chalkboard_session_123-123-123/index.html')->shouldBeCalled()->willReturn(false);
        $this->fileStorage->remove('/tmp/chalkboard_session_123-123-123')->shouldBeCalled();

        // Importer
        $contentImporter = new ContentImporter(
            $this->fileStorage->reveal(),
            $this->contentParser->reveal(),
            $this->imageMover->reveal(),
            $this->calculator->reveal(),
            $this->sessionRepository->reveal()
        );
        $contentImporter->import($course->reveal(), $uuid, $rank, $title, $uploadedFile, $dateTime, $folder);

        $this->cleanAfterTest();
    }

    public function testImport()
    {
        // Context
        $course = $this->prophesize(Course::class);
        $course->getUuid()->willReturn('321-321-321');
        $imagePath = '/content/course/321-321-321/session/123-123-123';
        $uploadLocation = '/tmp/chalkboard_session_123-123-123';
        $uuid = '123-123-123';
        $rank = 3;
        $title = 'title';
        $folder = null;
        $dateTime = new \Datetime();
        $uploadedFile = new UploadedFile(__DIR__ . '/content.zip', 'application/zip');

        $this->fileStorage->exists($uploadLocation . '/index.html')->shouldBeCalled()->willReturn(true);
        $contentParsed = new ContentParsedView('<h1>Hello World</h1>', ['image.jpg', 'image1.jpg', 'image2.jpg']);
        $this->contentParser
            ->parse($uploadLocation . '/index.html', $imagePath)
            ->shouldBeCalled()
            ->willReturn($contentParsed);

        $this->calculator->calculateSize('123-123-1233title')->shouldBeCalled()->willReturn(17);
        $this->sessionRepository->add(Argument::that(function (Session $session) use ($uuid, $title, $rank) {
            return $session->getUuid() === $uuid
                && $session->getTitle() === $title
                && $session->getRank() === $rank
            ;
        }))->shouldBeCalled();

        $this->imageMover
            ->moveImages(Argument::that(function (Session $session) use ($uuid, $title, $rank) {
                return $session->getUuid() === $uuid
                    && $session->getTitle() === $title
                    && $session->getRank() === $rank
                ;
            }), $uploadLocation, $imagePath, $contentParsed, $dateTime)
            ->shouldBeCalled()
            ->willReturn(10002)
        ;
        $this->calculator->calculateSize('<h1>Hello World</h1>')->shouldBeCalled()->willReturn(21);
        $this->sessionRepository->set(Argument::that(function (Session $session) use ($uuid, $title, $rank) {
            return $session->getUuid() === $uuid
                && $session->getTitle() === $title
                && $session->getRank() === $rank
                && $session->getContentSize() === 10023
            ;
        }))->shouldBeCalled();
        $this->fileStorage->remove($uploadLocation)->shouldBeCalled();

        // Importer
        $contentImporter = new ContentImporter(
            $this->fileStorage->reveal(),
            $this->contentParser->reveal(),
            $this->imageMover->reveal(),
            $this->calculator->reveal(),
            $this->sessionRepository->reveal()
        );
        $contentImporter->import($course->reveal(), $uuid, $rank, $title, $uploadedFile, $dateTime, $folder);

        $this->cleanAfterTest();
    }
}
