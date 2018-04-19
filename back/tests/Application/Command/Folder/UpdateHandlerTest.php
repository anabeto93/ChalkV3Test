<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Folder;

use App\Application\Command\Folder\Update;
use App\Application\Command\Folder\UpdateHandler;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;

class UpdateHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $dateTime = new \DateTime();
        $course = $this->prophesize(Course::class);
        $folder = new Folder('uuid-test', 0, 'title 1', $course->reveal(), new \DateTime('2017-08-01 10:10:10.000'), 16);

        // Expected
        $expectedFolder = new Folder(
            'uuid-test',
            1,
            'title 1',
            $course->reveal(),
            new \DateTime('2017-08-01 10:10:10.000'),
            16
        );
        $expectedFolder->update(1, 'new title', 18, $dateTime);

        // Mock
        $folderRepository = $this->prophesize(FolderRepositoryInterface::class);
        $calculator = $this->prophesize(Calculator::class);
        $calculator->calculateSize('uuid-testnew title')->shouldBeCalled()->willReturn(18);
        $folderRepository->set($expectedFolder)->shouldBeCalled();

        // Handler
        $command = new Update($folder);
        $command->rank = 1;
        $command->title = 'new title';
        $handler = new UpdateHandler(
            $folderRepository->reveal(),
            $calculator->reveal(),
            $dateTime
        );
        $handler->handle($command);
    }
}
