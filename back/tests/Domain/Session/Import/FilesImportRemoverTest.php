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
use App\Domain\Model\Session;
use App\Domain\Model\Session\File;
use App\Domain\Repository\Session\FileRepositoryInterface;
use App\Domain\Session\Import\FilesImportRemover;
use PHPUnit\Framework\TestCase;

class FilesImportRemoverTest extends TestCase
{
    public function testRemoveFiles()
    {
        $uploadDir = '/tmp/dir';
        $session = $this->prophesize(Session::class);
        $file1 = $this->prophesize(File::class);
        $file2 = $this->prophesize(File::class);

        // Mock
        $fileRepository = $this->prophesize(FileRepositoryInterface::class);
        $fileStorage = $this->prophesize(FileStorageInterface::class);
        $session->getFiles()->shouldBeCalled()->willReturn([$file1->reveal(), $file2->reveal()]);
        $session->setFiles([])->shouldBeCalled();
        $file1->getPath()->shouldBeCalled()->willReturn('/path/to/file/1');
        $file2->getPath()->shouldBeCalled()->willReturn('/path/to/file/2');

        $fileStorage->remove('/tmp/dir/path/to/file/1')->shouldBeCalled();
        $fileStorage->remove('/tmp/dir/path/to/file/2')->shouldBeCalled();
        $fileRepository->remove($file1->reveal())->shouldBeCalled();
        $fileRepository->remove($file2->reveal())->shouldBeCalled();

        $filesImportRemover = new FilesImportRemover(
            $fileStorage->reveal(),
            $fileRepository->reveal()
        );
        $filesImportRemover->removeFiles($session->reveal(), $uploadDir);
    }
}
