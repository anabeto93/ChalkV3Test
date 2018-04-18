<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\Course;

use App\Application\Query\Course\CourseListQuery;
use App\Application\Query\Course\CourseListQueryHandler;
use App\Application\View\Course\CourseView;
use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Model\Session;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CourseListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $course3 = $this->prophesize(Course::class);

        // Expected calls
        $course1->getId()->shouldBeCalled()->willReturn('1');
        $course2->getId()->shouldBeCalled()->willReturn('2');
        $course3->getId()->shouldBeCalled()->willReturn('3');

        $course1->getTitle()->shouldBeCalled()->willReturn('title 1');
        $course2->getTitle()->shouldBeCalled()->willReturn('title 2');
        $course3->getTitle()->shouldBeCalled()->willReturn('title 3');

        $course1->getTeacherName()->shouldBeCalled()->willReturn('teacher Name 1');
        $course2->getTeacherName()->shouldBeCalled()->willReturn('teacher Name 2');
        $course3->getTeacherName()->shouldBeCalled()->willReturn('teacher Name 3');

        $course1->getUniversity()->shouldBeCalled()->willReturn('University 1');
        $course2->getUniversity()->shouldBeCalled()->willReturn('University 2');
        $course3->getUniversity()->shouldBeCalled()->willReturn('University 3');

        $course1->isEnabled()->shouldBeCalled()->willReturn(true);
        $course2->isEnabled()->shouldBeCalled()->willReturn(false);
        $course3->isEnabled()->shouldBeCalled()->willReturn(false);


        // Mock
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $courseRepository->getAll()->shouldBeCalled()->willReturn([
            $course1->reveal(),
            $course2->reveal(),
            $course3->reveal(),
        ]);

        $handler = new CourseListQueryHandler($courseRepository->reveal());
        $result = $handler->handle(new CourseListQuery());

        $expected = [
            new CourseView(1, 'title 1', 'teacher Name 1', 'University 1', true),
            new CourseView(2, 'title 2', 'teacher Name 2', 'University 2', false),
            new CourseView(3, 'title 3', 'teacher Name 3', 'University 3', false),
        ];

        $this->assertEquals($expected, $result);
    }
}
