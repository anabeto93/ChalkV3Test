<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Folder;

use App\Application\Command\Folder\Delete;
use App\Application\Command\Folder\DeleteHandler;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteHandlerTest extends TestCase
{
    public function testHandle()
    {
        $folder = $this->prophesize(Folder::class);

        // Mock
        $folderRepository = $this->prophesize(FolderRepositoryInterface::class);
        $folderRepository->remove($folder->reveal())->shouldBeCalled();

        // Handler
        $handler = new DeleteHandler($folderRepository->reveal());
        $handler->handle(new Delete($folder->reveal()));
    }
}
