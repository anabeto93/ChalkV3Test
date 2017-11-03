<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Context\Domain;

use App\Domain\Model\Session\Question;
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\AnswerProxyInterface;

class AnswerContext implements Context
{
    /** @var AnswerProxyInterface */
    private $answerProxy;

    /**
     * @param AnswerProxyInterface $answerProxy
     */
    public function __construct(AnswerProxyInterface $answerProxy)
    {
        $this->answerProxy = $answerProxy;
    }

    /**
     * @Given /^there is a correct answer with the title "(?P<title>[^"]+)" for this question$/
     *
     * @param string $title
     */
    public function createCorrectAnswer(string $title)
    {
        $this->createAnswer($title, true);
    }

    /**
     * @Given /^there is an incorrect answer with the title "(?P<title>[^"]+)" for this question$/
     *
     * @param string $title
     */
    public function createIncorrectAnswer(string $title)
    {
        $this->createAnswer($title, false);
    }

    /**
     * @param string $title
     * @param bool   $correct
     */
    private function createAnswer(string $title, bool $correct)
    {
        $question = $this->answerProxy->getStorage()->get('question');

        if (!$question instanceof Question) {
            throw new \InvalidArgumentException('Question not found');
        }

        $answer = $this->answerProxy->getAnswerManager()->create($title, $correct, $question);
        $this->answerProxy->getStorage()->set('answer', $answer);
    }
}
