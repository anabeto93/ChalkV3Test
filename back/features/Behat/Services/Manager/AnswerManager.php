<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Session\Answer;
use App\Domain\Model\Session\Question;
use App\Domain\Repository\Session\AnswerRepositoryInterface;

class AnswerManager
{
    /** @var AnswerRepositoryInterface */
    private $answerRepository;

    /**
     * @param AnswerRepositoryInterface $answerRepository
     */
    public function __construct(AnswerRepositoryInterface $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param string   $title
     * @param bool     $correct
     * @param Question $question
     *
     * @return Answer
     */
    public function create(string $title, bool $correct, Question $question): Answer
    {
        $answer = new Answer(
            $title,
            $correct,
            $question,
            new \DateTime()
        );

        $this->answerRepository->add($answer);

        $question->addAnswer($answer);

        return $answer;
    }
}
