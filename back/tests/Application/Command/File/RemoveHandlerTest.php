<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\File;

use App\Application\Adapter\FileStorageInterface;
use App\Application\Command\File\Remove;
use App\Application\Command\File\RemoveHandler;
use App\Domain\Model\Upload\File;
use App\Domain\Repository\Upload\FileRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RemoveHandlerTest extends TestCase
{
    public function testHandleNoFile()
    {
        $file = $this->prophesize(File::class);
        $dir = '/tmp/';
        $file->getPath()->willReturn('path-to-file.csv');

        $fileStorage    = $this->prophesize(FileStorageInterface::class);
        $fileRepository = $this->prophesize(FileRepositoryInterface::class);

        $fileStorage->exists('/tmp/path-to-file.csv')->shouldBeCalled()->willReturn(false);
        $fileRepository->remove($file->reveal())->shouldBeCalled();

        $remove = new Remove($file->reveal(), $dir);
        $handler = new RemoveHandler(
            $fileStorage->reveal(),
            $fileRepository->reveal()
        );
        $handler->handle($remove);
    }

    public function testHandle()
    {
        $file = $this->prophesize(File::class);
        $dir = '/tmp/';
        $file->getPath()->willReturn('path-to-file.csv');

        $fileStorage    = $this->prophesize(FileStorageInterface::class);
        $fileRepository = $this->prophesize(FileRepositoryInterface::class);

        $fileStorage->exists('/tmp/path-to-file.csv')->shouldBeCalled()->willReturn(true);
        $fileStorage->remove('/tmp/path-to-file.csv')->shouldBeCalled();
        $fileRepository->remove($file->reveal())->shouldBeCalled();

        $remove = new Remove($file->reveal(), $dir);
        $handler = new RemoveHandler(
            $fileStorage->reveal(),
            $fileRepository->reveal()
        );
        $handler->handle($remove);
    }
}
