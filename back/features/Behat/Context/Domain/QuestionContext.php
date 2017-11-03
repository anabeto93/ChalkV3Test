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
use Behat\Behat\Context\Context;
use Features\Behat\Domain\Proxy\QuestionProxyInterface;

class QuestionContext implements Context
{
    /** @var QuestionProxyInterface */
    private $questionProxy;

    /**
     * @param QuestionProxyInterface $questionProxy
     */
    public function __construct(QuestionProxyInterface $questionProxy)
    {
        $this->questionProxy = $questionProxy;
    }

    /**
     * @Given /^there is a question with the title "(?P<title>[^"]+)" for this session$/
     *
     * @param string $title
     */
    public function createQuestion(string $title)
    {
        $session = $this->questionProxy->getStorage()->get('session');

        if (!$session instanceof Session) {
            throw new \InvalidArgumentException('Session not found');
        }
        $question = $this->questionProxy->getQuestionManager()->create($title, $session);
        $this->questionProxy->getStorage()->set('question', $question);
    }
}
