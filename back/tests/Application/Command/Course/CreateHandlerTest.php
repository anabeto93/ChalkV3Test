<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Course;

use App\Application\Command\Course\Create;
use App\Application\Command\Course\CreateHandler;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $dateTime = new \DateTime();

        $command = new Create();
        $command->title = 'title';
        $command->teacherName = 'teacherName';
        $command->university = 'university';
        $command->description = 'this is the description';
        $command->enabled = true;

        // Expected
        $expectedCourse = new Course(
            '12345abc-def67-890ik',
            'title',
            'teacherName',
            'university',
            true,
            $dateTime,
            'this is the description',
            49
        );

        // Mock
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $courseRepository->add($expectedCourse)->shouldBeCalled();
        $generator = $this->prophesize(Generator::class);
        $generator->generateUuid()->shouldBeCalled()->willReturn('12345abc-def67-890ik');

        $handler = new CreateHandler(
            $courseRepository->reveal(),
            $generator->reveal(),
            $dateTime
        );
        $handler->handle($command);
    }
}
