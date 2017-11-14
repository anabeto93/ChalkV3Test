<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context\Domain;

use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\SessionQuizResult;
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\SessionQuizResultProxyInterface;

class SessionQuizResultContext implements Context
{
    /** @var SessionQuizResultProxyInterface */
    private $sessionQuizResultProxy;

    /**
     * @param SessionQuizResultProxyInterface $sessionQuizResultProxy
     */
    public function __construct(SessionQuizResultProxyInterface $sessionQuizResultProxy)
    {
        $this->sessionQuizResultProxy = $sessionQuizResultProxy;
    }

    /**
     * @Then the session quiz result for this user is :correctAnswers \/ :questionsNumber
     *
     * @param int $correctAnswers
     * @param int $questionsNumber
     */
    public function theSessionQuizResultForThisUserIs(int $correctAnswers, int $questionsNumber)
    {
        $user = $this->sessionQuizResultProxy->getStorage()->get('user');

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found');
        }

        $session = $this->sessionQuizResultProxy->getStorage()->get('session');

        if (!$session instanceof Session) {
            throw new \InvalidArgumentException('Session not found');
        }

        $sessionQuizResult = $this->sessionQuizResultProxy->getSessionQuizResultManager()->findByUserAndSession(
            $user,
            $session
        );

        if (!$sessionQuizResult instanceof SessionQuizResult) {
            throw new \DomainException('SessionQuizResult not found for this user and this session');
        }

        if ($sessionQuizResult->getCorrectAnswersNumber() !== $correctAnswers
            || $sessionQuizResult->getQuestionsNumber() !== $questionsNumber
        ) {
            throw new \DomainException(
                'Values of SessionQuizResult for this user and this session are not as expected'
            );
        }
    }
}
