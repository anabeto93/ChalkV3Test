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
use App\Domain\Model\Institution;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Size\Calculator;
use App\Domain\Uuid\Generator;
use PHPUnit\Framework\TestCase;

class CreateHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Context
        $institution = $this->prophesize(Institution::class);

        $dateTime = new \DateTime();

        $command = new Create($institution->reveal());
        $command->title = 'title';
        $command->teacherName = 'teacherName';
        $command->description = 'this is the description';
        $command->enabled = true;

        // Expected
        $expectedCourse = new Course(
            '12345abc-def67-890ik',
            $institution->reveal(),
            'title',
            'teacherName',
            true,
            $dateTime,
            'this is the description',
            59
        );

        // Mock
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $courseRepository->add($expectedCourse)->shouldBeCalled();
        $generator = $this->prophesize(Generator::class);
        $generator->generateUuid()->shouldBeCalled()->willReturn('12345abc-def67-890ik');
        $sizeCalculator = $this->prophesize(Calculator::class);
        $sizeCalculator
            ->calculateSize('12345abc-def67-890iktitleteacherNamethis is the description')
            ->shouldBeCalled()
            ->willReturn(59)
        ;

        $handler = new CreateHandler(
            $courseRepository->reveal(),
            $generator->reveal(),
            $sizeCalculator->reveal(),
            $dateTime
        );
        $handler->handle($command);
    }
}
