<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Query\Session;

use App\Application\Query\Session\ProgressionListQuery;
use App\Application\Query\Session\ProgressionListQueryHandler;
use App\Application\View\Session\Progression\UserValidatedView;
use App\Application\View\Session\Progression\UserView;
use App\Application\View\Session\ProgressionListView;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\UserCourse;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Repository\UserCourseRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ProgressionListQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $date        = new \DateTime();
        $session     = $this->prophesize(Session::class);
        $course      = $this->prophesize(Course::class);
        $user1       = $this->prophesize(User::class);
        $user2       = $this->prophesize(User::class);
        $user3       = $this->prophesize(User::class);
        $userCourse1 = $this->prophesize(UserCourse::class);
        $userCourse2 = $this->prophesize(UserCourse::class);
        $userCourse3 = $this->prophesize(UserCourse::class);
        $progression1 = $this->prophesize(User\Progression::class);
        $session->getCourse()->willReturn($course->reveal());
        $userCourse1->getUser()->willReturn($user1->reveal());
        $userCourse2->getUser()->willReturn($user2->reveal());
        $userCourse3->getUser()->willReturn($user3->reveal());
        $progression1->getUser()->willReturn($user1->reveal());
        $user1->getId()->willReturn(18);
        $user2->getId()->willReturn(23);
        $user3->getId()->willReturn(25);

        $user1->getFirstName()->willReturn('first name 1');
        $user1->getLastName()->willReturn('last name 1');
        $user1->getPhoneNumber()->willReturn('+33123123123');
        $progression1->getCreatedAt()->willReturn($date);
        $user2->getFirstName()->willReturn('Toto');
        $user2->getLastName()->willReturn('Tata');
        $user2->getPhoneNumber()->willReturn('+33987654321');
        $user3->getFirstName()->willReturn('Titi');
        $user3->getLastName()->willReturn('Aaaaa');
        $user3->getPhoneNumber()->willReturn('+335678912345');

        $userCourseRepository  = $this->prophesize(UserCourseRepositoryInterface::class);
        $progressionRepository = $this->prophesize(ProgressionRepositoryInterface::class);

        $userCourseRepository
            ->findByCourse($course->reveal())
            ->shouldBeCalled()
            ->willReturn([
                $userCourse1,
                $userCourse2,
                $userCourse3,
            ])
        ;

        $progressionRepository
            ->findForSession($session->reveal())
            ->shouldBeCalled()
            ->willReturn([$progression1->reveal()])
        ;

        $query   = new ProgressionListQuery($session->reveal());
        $handler = new ProgressionListQueryHandler(
            $userCourseRepository->reveal(),
            $progressionRepository->reveal()
        );
        $result = $handler->handle($query);

        $expected = new ProgressionListView();
        $expected->addUserValidated(new UserValidatedView('last name 1', 'first name 1', '33123123123', $date));
        $expected->addUserNotValidated(new UserView('Aaaaa', 'Titi', '+335678912345'));
        $expected->addUserNotValidated(new UserView('Tata', 'Toto', '+33987654321'));

        $this->assertEquals($expected, $result);
    }
}
