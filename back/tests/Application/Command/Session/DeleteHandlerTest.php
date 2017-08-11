<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Session;

use App\Application\Adapter\FileStorageInterface;
use App\Application\Command\Session\Delete;
use App\Application\Command\Session\DeleteHandler;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\FileRepositoryInterface;
use App\Domain\Repository\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteHandlerTest extends TestCase
{
    public function testHandle()
    {
        $uploadDir = '/tmp/dir';
        $session = $this->prophesize(Session::class);
        $file1 = $this->prophesize(Session\File::class);
        $file2 = $this->prophesize(Session\File::class);

        // Mock
        $sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $fileRepository = $this->prophesize(FileRepositoryInterface::class);
        $fileStorage = $this->prophesize(FileStorageInterface::class);
        $session->getFiles()->shouldBeCalled()->willReturn([$file1->reveal(), $file2->reveal()]);
        $file1->getPath()->shouldBeCalled()->willReturn('/path/to/file/1');
        $file2->getPath()->shouldBeCalled()->willReturn('/path/to/file/2');

        $fileStorage->remove('/tmp/dir/path/to/file/1')->shouldBeCalled();
        $fileStorage->remove('/tmp/dir/path/to/file/2')->shouldBeCalled();
        $fileRepository->remove($file1->reveal())->shouldBeCalled();
        $fileRepository->remove($file2->reveal())->shouldBeCalled();
        $sessionRepository->remove($session->reveal())->shouldBeCalled();

        // Handler
        $handler = new DeleteHandler(
            $fileRepository->reveal(),
            $sessionRepository->reveal(),
            $fileStorage->reveal(),
            $uploadDir
        );
        $handler->handle(new Delete($session->reveal()));
    }
}
