<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Folder;

use App\Application\Command\Folder\Create;
use App\Application\Command\Folder\CreateHandler;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $dateTime = new \DateTime();
        $course = $this->prophesize(Course::class);

        // Expected
        $expectedFolder = new Folder('uuid-test', 0, 'title 1', $course->reveal(), $dateTime,
            16);

        // Mock
        $folderRepository = $this->prophesize(FolderRepositoryInterface::class);
        $generator = $this->prophesize(Generator::class);
        $calculator = $this->prophesize(Calculator::class);
        $generator->generateUuid()->shouldBeCalled()->willReturn('uuid-test');
        $calculator->calculateSize('title 1uuid-test')->shouldBeCalled()->willReturn(16);
        $folderRepository->add($expectedFolder)->shouldBeCalled();

        // Handler
        $command = new Create($course->reveal());
        $command->title = 'title 1';
        $handler = new CreateHandler(
            $folderRepository->reveal(),
            $generator->reveal(),
            $dateTime,
            $calculator->reveal()
        );
        $handler->handle($command);
    }
}
