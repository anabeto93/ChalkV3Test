<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Session;

use App\Application\Command\Session\Delete;
use App\Application\Command\Session\DeleteHandler;
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Session\Import\FilesImportRemover;
use PHPUnit\Framework\TestCase;

class DeleteHandlerTest extends TestCase
{
    public function testHandle()
    {
        $uploadDir = '/tmp/dir';
        $session = $this->prophesize(Session::class);

        // Mock
        $filesImportRemover = $this->prophesize(FilesImportRemover::class);
        $filesImportRemover->removeFiles($session->reveal(), $uploadDir)->shouldBeCalled();
        $sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $sessionRepository->remove($session->reveal())->shouldBeCalled();

        // Handler
        $handler = new DeleteHandler(
            $filesImportRemover->reveal(),
            $sessionRepository->reveal(),
            $uploadDir
        );
        $handler->handle(new Delete($session->reveal()));
    }
}
