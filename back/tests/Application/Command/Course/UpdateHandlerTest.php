<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Course;

use App\Application\Command\Course\Update;
use App\Application\Command\Course\UpdateHandler;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Size\Calculator;
use PHPUnit\Framework\TestCase;
use App\Application\Command\User\ForceUpdate;
use App\Application\Command\User\ForceUpdateHandler;

class UpdateHandlerTest extends TestCase
{
    public function testHandle()
    {
        $course = new Course(
            '12345abc-def67-890ik',
            'title',
            'teacherName',
            'university',
            false,
            new \DateTime('2017-08-01 10:10:10.000'),
            'this is the description',
            69
        );

        // Context
        $dateTime = new \DateTime();

        $command = new Update($course);
        $command->title = 'title 2';
        $command->teacherName = 'name of teacher';
        $command->university = 'university name';
        $command->description = '';
        $command->enabled = true;

        // Expected
        $expectedCourse = new Course(
            '12345abc-def67-890ik',
            'title',
            'teacherName',
            'university',
            false,
            new \DateTime('2017-08-01 10:10:10.000'),
            'this is the description',
            69
        );
        $expectedCourse->update(
            'title 2',
            '',
            'name of teacher',
            'university name',
            true,
            57,
            $dateTime
        );

        // Mock
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $forceUpdateHandler = $this->prophesize(ForceUpdateHandler::class);
        $courseRepository->set($expectedCourse)->shouldBeCalled();
        $sizeCalculator = $this->prophesize(Calculator::class);
        $sizeCalculator
            ->calculateSize('12345abc-def67-890iktitle 2name of teacheruniversity name')
            ->shouldBeCalled()
            ->willReturn(57)
        ;

        $forceUpdateHandler->handle(
            new ForceUpdate([])
        )->shouldBeCalled();

        $handler = new UpdateHandler(
            $courseRepository->reveal(),
            $forceUpdateHandler->reveal(),
            $sizeCalculator->reveal(),
            $dateTime
        );
        $handler->handle($command);
    }
}
