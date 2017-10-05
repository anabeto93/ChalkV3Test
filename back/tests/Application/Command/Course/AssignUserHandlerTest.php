<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Course;

use App\Application\Command\Course\AssignUser;
use App\Application\Command\Course\AssignUserHandler;
use App\Domain\Model\Course;
use App\Domain\Model\User;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\CourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AssignUserHandlerTest extends TestCase
{
    public function testHandle()
    {
        $datetime = new \DateTime();

        $course = $this->prophesize(Course::class);
        $user1 = $this->prophesize(User::class);
        $user2 = $this->prophesize(User::class);

        // Previous users assigned was only User2
        $course->getUsers()->shouldBeCalled()->willReturn([$user2->reveal()]);

        // User1 is assigned
        $course->addUserCourse(new UserCourse($user1->reveal(), $course->reveal(), $datetime))->shouldBeCalled();

        // User2 is unassigned
        $course->removeUserCourse($user2->reveal(), $course->reveal())->shouldBeCalled();

        $courseRepository = $this->prophesize(CourseRepositoryInterface::class);
        $courseRepository->set($course->reveal())->shouldBeCalled();

        $handler = new AssignUserHandler($courseRepository->reveal(), $datetime);
        $assignUser = new AssignUser($course->reveal());
        // User1 is assigned by the command
        $assignUser->users = [$user1->reveal()];

        $handler->handle($assignUser);
    }
}
