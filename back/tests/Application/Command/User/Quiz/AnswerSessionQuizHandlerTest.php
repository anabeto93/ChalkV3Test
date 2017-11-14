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
use App\Domain\Exception\User\Quiz\SessionQuizAnswerAlreadyExistsException;
use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\SessionQuizResult;
use App\Domain\Quiz\Answers\QuizAnswersTransformer;
use App\Domain\Quiz\Answers\Views\QuestionView;
use App\Domain\Quiz\Answers\Views\QuizAnswerView;
use App\Domain\Quiz\Result\QuizResultCalculator;
use App\Domain\Quiz\Result\Views\QuizResultView;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class AnswerSessionQuizHandlerTest extends TestCase
{
    /** @var ObjectProphecy */
    private $sessionRepository;

    /** @var ObjectProphecy */
    private $sessionQuizResultRepository;

    /** @var ObjectProphecy */
    private $quizResultCalculator;

    /** @var ObjectProphecy */
    private $quizAnswersTransformer;

    /** @var \DateTime */
    private $dateTime;

    public function setUp()
    {
        $this->sessionRepository           = $this->prophesize(SessionRepositoryInterface::class);
        $this->sessionQuizResultRepository = $this->prophesize(SessionQuizResultRepositoryInterface::class);
        $this->quizResultCalculator        = $this->prophesize(QuizResultCalculator::class);
        $this->quizAnswersTransformer      = $this->prophesize(QuizAnswersTransformer::class);
        $this->dateTime                    = new \DateTime();
    }

    public function testHandleNotFound()
    {
        $this->setExpectedException(SessionNotFoundException::class);

        $user = $this->prophesize(User::class);

        $this->sessionRepository->getByUuid('not-found')->shouldBeCalled()->willReturn(null);

        $handler = new AnswerSessionQuizHandler(
            $this->sessionRepository->reveal(),
            $this->sessionQuizResultRepository->reveal(),
            $this->quizResultCalculator->reveal(),
            $this->quizAnswersTransformer->reveal(),
            $this->dateTime
        );
        $handler->handle(new AnswerSessionQuiz($user->reveal(), 'not-found', '1;2,3', 'web'));
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
            $this->sessionQuizResultRepository->reveal(),
            $this->quizResultCalculator->reveal(),
            $this->quizAnswersTransformer->reveal(),
            $this->dateTime
        );
        $handler->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3', 'web'));
    }

    public function testSessionQuizAnswerAlreadyExistsException()
    {
        $this->setExpectedException(SessionQuizAnswerAlreadyExistsException::class);

        $sessionQuizResult = $this->prophesize(SessionQuizResult::class);
        $user              = $this->prophesize(User::class);
        $session           = $this->prophesize(Session::class);
        $course            = $this->prophesize(Course::class);
        $session->getCourse()->willReturn($course->reveal());
        $user->hasCourse($course->reveal())->shouldBeCalled()->willReturn(true);

        $this->sessionRepository->getByUuid('123-123')->shouldBeCalled()->willReturn($session);

        $handler = new AnswerSessionQuizHandler(
            $this->sessionRepository->reveal(),
            $this->sessionQuizResultRepository->reveal(),
            $this->quizResultCalculator->reveal(),
            $this->quizAnswersTransformer->reveal(),
            $this->dateTime
        );

        $this
            ->sessionQuizResultRepository
            ->findByUserAndSession($user->reveal(), $session->reveal())
            ->shouldBeCalled()
            ->willReturn($sessionQuizResult->reveal())
        ;

        $this
            ->sessionQuizResultRepository
            ->add()
            ->shouldNotBeCalled()
        ;

        $this->assertTrue($handler->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3', 'web')));
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
            $this->sessionQuizResultRepository->reveal(),
            $this->quizResultCalculator->reveal(),
            $this->quizAnswersTransformer->reveal(),
            $this->dateTime
        );

        $this
            ->sessionQuizResultRepository
            ->findByUserAndSession($user->reveal(), $session->reveal())
            ->shouldBeCalled()
            ->willReturn(null)
        ;

        $quizAnswerView = $this->prophesize(QuizAnswerView::class);

        $this
            ->quizAnswersTransformer
            ->transform('1;2,3')
            ->shouldBeCalled()
            ->willReturn($quizAnswerView->reveal())
        ;

        $this
            ->quizResultCalculator
            ->getQuizResultView($session->reveal(), $quizAnswerView->reveal())
            ->shouldBeCalled()
            ->willReturn(new QuizResultView(1, 2))
        ;

        $this
            ->sessionQuizResultRepository
            ->add(new SessionQuizResult($user->reveal(), $session->reveal(), 'web', 1, 2, $this->dateTime))
            ->shouldBeCalled()
        ;

        $this->assertTrue($handler->handle(new AnswerSessionQuiz($user->reveal(), '123-123', '1;2,3', 'web')));
    }
}
