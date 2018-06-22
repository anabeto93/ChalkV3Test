<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\User\Quiz;

use App\Domain\Exception\Session\SessionHasNotQuizException;
use App\Domain\Exception\Session\SessionNotAccessibleForThisUserException;
use App\Domain\Exception\Session\SessionNotFoundException;
use App\Domain\Exception\User\Quiz\SessionQuizAnswerAlreadyExistsException;
use App\Domain\Model\Session;
use App\Domain\Model\User\SessionQuizResult;
use App\Domain\Quiz\Answers\QuizAnswersTransformer;
use App\Domain\Quiz\Result\QuizResultCalculator;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Domain\Repository\SessionRepositoryInterface;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;

class AnswerSessionQuizHandler
{
    /** @var SessionRepositoryInterface */
    private $sessionRepository;

    /** @var SessionQuizResultRepositoryInterface */
    private $sessionQuizResultRepository;

    /** @var QuizResultCalculator */
    private $quizResultCalculator;

    /** @var QuizAnswersTransformer */
    private $quizAnswersTransformer;

    /** @var \DateTimeInterface */
    private $dateTime;

    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /**
     * @param SessionRepositoryInterface           $sessionRepository
     * @param SessionQuizResultRepositoryInterface $sessionQuizResultRepository
     * @param QuestionRepositoryInterface          $questionRepository
     * @param QuizResultCalculator                 $quizResultCalculator
     * @param QuizAnswersTransformer               $quizAnswersTransformer
     * @param \DateTimeInterface                   $dateTime
     */
    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        SessionQuizResultRepositoryInterface $sessionQuizResultRepository,
        QuestionRepositoryInterface $questionRepository,
        QuizResultCalculator $quizResultCalculator,
        QuizAnswersTransformer $quizAnswersTransformer,
        \DateTimeInterface $dateTime
    ) {
        $this->sessionRepository           = $sessionRepository;
        $this->sessionQuizResultRepository = $sessionQuizResultRepository;
        $this->questionRepository          = $questionRepository;
        $this->quizResultCalculator        = $quizResultCalculator;
        $this->quizAnswersTransformer      = $quizAnswersTransformer;
        $this->dateTime                    = $dateTime;
    }

    /**
     * @param AnswerSessionQuiz $answerSessionQuiz
     *
     * @throws SessionHasNotQuizException
     * @throws SessionNotFoundException
     * @throws SessionNotAccessibleForThisUserException
     * @throws SessionQuizAnswerAlreadyExistsException
     */
    public function handle(AnswerSessionQuiz $answerSessionQuiz)
    {
        $session = $this->sessionRepository->getByUuid($answerSessionQuiz->sessionUuid);

        if (!$session instanceof Session) {
            throw new SessionNotFoundException(
                sprintf('The session "%s" can not be found', $answerSessionQuiz->sessionUuid)
            );
        }

        if (!$answerSessionQuiz->user->hasCourse($session->getCourse())) {
            throw new SessionNotAccessibleForThisUserException(
                sprintf('The session "%s" not accessible for this user', $answerSessionQuiz->sessionUuid)
            );
        }

        if (!$this->questionRepository->sessionHasQuiz($session)) {
            throw new SessionHasNotQuizException(
                sprintf('The session "%s" has not quiz', $answerSessionQuiz->sessionUuid)
            );
        }

        $sessionQuizResult = $this->sessionQuizResultRepository->findByUserAndSession(
            $answerSessionQuiz->user,
            $session
        );

        if ($sessionQuizResult instanceof SessionQuizResult) {
            throw new SessionQuizAnswerAlreadyExistsException(
                sprintf('A quiz answer for session "%s" already exists for this user', $answerSessionQuiz->sessionUuid)
            );
        }

        $quizAnswerView = $this->quizAnswersTransformer->transform($answerSessionQuiz->answers);
        $quizResultView = $this->quizResultCalculator->getQuizResultView($session, $quizAnswerView);

        $sessionQuizResult = new SessionQuizResult(
            $answerSessionQuiz->user,
            $session,
            $answerSessionQuiz->medium,
            $quizResultView->correctAnswersNumber,
            $quizResultView->questionsNumber,
            explode(';', $answerSessionQuiz->answers),
            $quizResultView->questionsResult,
            $this->dateTime
        );

        $this->sessionQuizResultRepository->add($sessionQuizResult);
    }
}
