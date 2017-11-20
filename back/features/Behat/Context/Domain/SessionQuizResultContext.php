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
        $sessionQuizResult = $this->getSessionQuizResult();

        if ($sessionQuizResult->getCorrectAnswersNumber() !== $correctAnswers
            || $sessionQuizResult->getQuestionsNumber() !== $questionsNumber
        ) {
            throw new \DomainException(
                'Values of SessionQuizResult for this user and this session are not as expected'
            );
        }
    }

    /**
     * @Then the session quiz result by question is: :result
     *
     * :result expected is correct|incorrect by question separated by a comma: "correct, incorrect, correct"
     *
     * @param string $result
     */
    public function theSessionQuizResultByQuestionIs(string $result)
    {
        $sessionQuizResult = $this->getSessionQuizResult();

        $results = explode(',', $result);

        $expectedResults = array_map(function (string $result) {
            return trim($result) === 'correct';
        }, $results);

        if ($sessionQuizResult->getQuestionsResult() !== $expectedResults) {
            throw new \DomainException(
                'Questions result for this user and this session are not as expected'
            );
        }
    }

    private function getSessionQuizResult(): SessionQuizResult
    {
        $user = $this->sessionQuizResultProxy->getStorage()->get('user');

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User not found');
        }

        $session = $this->sessionQuizResultProxy->getStorage()->get('session');

        if (!$session instanceof Session) {
            throw new \InvalidArgumentException('Session not found');
        }

        $storedSessionQuizResult = $this->sessionQuizResultProxy->getStorage()->get('sessionQuizResult');

        if ($storedSessionQuizResult instanceof SessionQuizResult
            && $storedSessionQuizResult->getUser()->getId() === $user->getId()
            && $storedSessionQuizResult->getSession()->getId() === $session->getId()
        ) {
            return $storedSessionQuizResult;
        }

        $sessionQuizResult = $this->sessionQuizResultProxy->getSessionQuizResultManager()->findByUserAndSession(
            $user,
            $session
        );

        if (!$sessionQuizResult instanceof SessionQuizResult) {
            throw new \DomainException('SessionQuizResult not found for this user and this session');
        }

        $this->sessionQuizResultProxy->getStorage()->set('sessionQuizResult', $sessionQuizResult);

        return $sessionQuizResult;
    }
}
