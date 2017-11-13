<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User\Progression;

use App\Application\Command\User\Quiz\AnswerSessionQuiz;
use App\Application\Command\User\Quiz\AnswerSessionQuizHandler;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class AnswerSessionQuizHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var \DateTime */
    private $dateTime;

    public function setUp()
    {
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $this->dateTime          = new \DateTime();
    }

    public function testHandleNotFound()
    {
        $this->setExpectedException(SessionNotFoundException::class);

        $user = $this->prophesize(User::class);

        $this->sessionRepository->getByUuid('not-found')->shouldBeCalled()->willReturn(null);

        $handler = new AnswerSessionQuizHandler(
            $this->sessionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle(new AnswerSessionQuiz($user->reveal(), 'not-found', '1;2,3'));
    }

    public function testHandleNotAccessible()
    {
        $this->setExpectedException(SessionNotAccessibleForThisUserException::class);

        $user    = $this->prophesize(User::class);
        $session = $this->prophesize(Session::class);
        $course  = $this->prophesize(Course::class);
        $session->getCourse()->willReturn($course->reveal());
        $user->hasCourse($course->reveal())->shouldBeCalled()->willReturn(false);

        $this->sessionRepository->getByUuid('123-123')->shouldBeCalled()->willReturn($session);

        $handler = new AnswerSessionQuizHandler(
            $this->sessionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3'));
    }

    public function testHandle()
    {
        $user    = $this->prophesize(User::class);
        $session = $this->prophesize(Session::class);
        $course  = $this->prophesize(Course::class);
        $session->getCourse()->willReturn($course->reveal());
        $user->hasCourse($course->reveal())->shouldBeCalled()->willReturn(true);

        $this->sessionRepository->getByUuid('123-123')->shouldBeCalled()->willReturn($session);

        $handler = new AnswerSessionQuizHandler(
            $this->sessionRepository->reveal(),
            $this->dateTime
        );

        $this->assertTrue($handler->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3')));
    }
}
