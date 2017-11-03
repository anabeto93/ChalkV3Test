<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Session;
use App\Domain\Repository\Session\QuestionRepositoryInterface;

class QuestionManager
{
    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /**
     * @param QuestionRepositoryInterface $questionRepository
     */
    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param string   $title
     * @param Session $session
     *
     * @return Session\Question
     */
    public function create(string $title, Session $session): Session\Question
    {
        $question = new Session\Question(
            $title,
            $session,
            new \DateTime()
        );

        $this->questionRepository->add($question);

        return $question;
    }
}
