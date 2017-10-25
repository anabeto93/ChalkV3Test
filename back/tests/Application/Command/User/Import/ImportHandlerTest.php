<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User\Import;

use App\Application\Adapter\FileStorageInterface;
use App\Application\Command\User\Import\Import;
use App\Application\Command\User\Import\ImportHandler;
use App\Domain\Charset\Charset;
use App\Domain\Model\Upload\File;
use App\Domain\Repository\Upload\FileRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportHandlerTest extends TestCase
{
    public function testHandle()
    {
        $file = $this
            ->getMockBuilder(UploadedFile::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([tempnam(sys_get_temp_dir(), ''), 'csv'])
            ->getMock()
        ;
        $dateTime = new \DateTime();
        $importDir = '/tmp/';
        $fileRepository = $this->prophesize(FileRepositoryInterface::class);
        $fileStorage = $this->prophesize(FileStorageInterface::class);

        $fileStorage->changeEncoding($file, Charset::WINDOWS_1252, Charset::UTF_8)->shouldBeCalled();
        $fileStorage
            ->upload($file, '/tmp/')
            ->shouldBeCalled()
            ->willReturn('/tmp/path-to-file.csv')
        ;

        $expectFile = new File('/tmp/path-to-file.csv', $dateTime);
        $fileRepository->add($expectFile)->shouldBeCalled();

        $importHandler = new ImportHandler(
            $fileRepository->reveal(),
            $fileStorage->reveal(),
            $importDir,
            $dateTime
        );
        $import = new Import();
        $import->file = $file;
        $import->charset = Charset::WINDOWS_1252;
        $importHandler->handle($import);
    }
}
