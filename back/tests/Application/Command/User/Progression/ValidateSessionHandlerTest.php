<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\User\Progression;

use App\Application\Command\User\Progression\ValidateSession;
use App\Application\Command\User\Progression\ValidateSessionHandler;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use App\Domain\Exception\Session\ValidateSession\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\ValidateSession\SessionNotFoundException;
use App\Domain\User\Progression\Medium;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class ValidateSessionHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var ObjectProphecy */
    private $progressionRepository;

    /** @var \DateTime */
    private $dateTime;

    public function setUp()
    {
        $this->sessionRepository = $this->prophesize(SessionRepositoryInterface::class);
        $this->progressionRepository = $this->prophesize(ProgressionRepositoryInterface::class);
        $this->dateTime = new \DateTime();
    }

    public function testHandleNotFound()
    {
        $this->setExpectedException(SessionNotFoundException::class);

        $user = $this->prophesize(User::class);
        $sessionUuid = 'not-found';

        $this->sessionRepository->getByUuid($sessionUuid)->shouldBeCalled()->willReturn(null);

        $handler = new ValidateSessionHandler(
            $this->sessionRepository->reveal(),
            $this->progressionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB));
    }

    public function testHandleNotAccessible()
    {
        $this->setExpectedException(SessionNotAccessibleForThisUserException::class);

        $user = $this->prophesize(User::class);
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $session->getCourse()->willReturn($course->reveal());
        $sessionUuid = '123-123';
        $user->hasCourse($course->reveal())->shouldBeCalled()->willReturn(false);

        $this->sessionRepository->getByUuid($sessionUuid)->shouldBeCalled()->willReturn($session);

        $handler = new ValidateSessionHandler(
            $this->sessionRepository->reveal(),
            $this->progressionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB));
    }

    public function testHandle()
    {
        $user = $this->prophesize(User::class);
        $session = $this->prophesize(Session::class);
        $course = $this->prophesize(Course::class);
        $session->getCourse()->willReturn($course->reveal());
        $sessionUuid = '123-123';
        $user->hasCourse($course->reveal())->shouldBeCalled()->willReturn(true);

        $this->sessionRepository->getByUuid($sessionUuid)->shouldBeCalled()->willReturn($session);
        $this->progressionRepository
            ->findByUserAndSession($user->reveal(), $session->reveal())
            ->shouldBeCalled()
            ->willReturn(null)
        ;
        $progression = new User\Progression($user->reveal(), $session->reveal(), Medium::WEB, $this->dateTime);
        $this->progressionRepository->add($progression)->shouldBeCalled();

        $handler = new ValidateSessionHandler(
            $this->sessionRepository->reveal(),
            $this->progressionRepository->reveal(),
            $this->dateTime
        );
        $handler->handle(new ValidateSession($user->reveal(), $sessionUuid, Medium::WEB));
    }
}
