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
use App\Domain\Model\Institution;
use App\Domain\Model\Session;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CourseListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $institution = $this->prophesize(Institution::class);
        $course1 = $this->prophesize(Course::class);
        $course2 = $this->prophesize(Course::class);
        $course3 = $this->prophesize(Course::class);
        $folder1 = $this->prophesize(Folder::class);
        $folder2 = $this->prophesize(Folder::class);
        $folder3 = $this->prophesize(Folder::class);
        $session1 = $this->prophesize(Session::class);
        $session2 = $this->prophesize(Session::class);
        $session3 = $this->prophesize(Session::class);
        $session4 = $this->prophesize(Session::class);

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

        $course1->isEnabled()->shouldBeCalled()->willReturn(true);
        $course2->isEnabled()->shouldBeCalled()->willReturn(false);
        $course3->isEnabled()->shouldBeCalled()->willReturn(false);

        $course1->getFolders()->shouldBeCalled()->willReturn([$folder1->reveal()]);
        $course2->getFolders()->shouldBeCalled()->willReturn([]);
        $course3->getFolders()->shouldBeCalled()->willReturn([$folder2->reveal(), $folder3->reveal()]);

        $course1->getSessions()->shouldBeCalled()->willReturn([$session1->reveal()]);
        $course2->getSessions()->shouldBeCalled()->willReturn([]);
        $course3->getSessions()->shouldBeCalled()->willReturn([$session2->reveal(), $session3->reveal(), $session4->reveal()]);

        $course1->getUsers()->shouldBeCalled()->willReturn([]);
        $course2->getUsers()->shouldBeCalled()->willReturn([]);
        $course3->getUsers()->shouldBeCalled()->willReturn([]);

        // Mock
        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $courseRepository->findByInstitution($institution->reveal())->shouldBeCalled()->willReturn([
            $course1->reveal(),
            $course2->reveal(),
            $course3->reveal(),
        ]);

        $handler = new CourseListQueryHandler($courseRepository->reveal());
        $result = $handler->handle(new CourseListQuery($institution->reveal()));

        $expected = [
            new CourseView(1, 'title 1', 'teacher Name 1', true, 1, 1, 0),
            new CourseView(2, 'title 2', 'teacher Name 2', false, 0, 0, 0),
            new CourseView(3, 'title 3', 'teacher Name 3', false, 2, 3, 0),
        ];

        $this->assertEquals($expected, $result);
    }
}
