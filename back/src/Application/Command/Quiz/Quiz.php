<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Quiz;

use App\Application\Command\Command;
use App\Domain\Model\Session;

class Quiz implements Command
{
    /** @var Session */
    public $session;

    /** @var array */
    public $questions;

    /** @var Session\Question[] */
    public $originalQuestions;

    /**
     * @param Session            $session
     * @param Session\Question[] $questions
     */
    public function __construct(Session $session, array $questions = [])
    {
        $this->session = $session;

        $this->originalQuestions = $questions;
        $this->questions = [];

        foreach ($questions as $question) {
            $this->questions[] = [
                'title'   => $question->getTitle(),
                'answers' => array_map(function (Session\Answer $answer) {
                    return [
                        'title' => $answer->getTitle(),
                        'correct' => $answer->isCorrect(),
                    ];
                }, $question->getAnswers()),
            ];
        }
    }
}
