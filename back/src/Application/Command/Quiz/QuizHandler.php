<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Quiz;

use App\Domain\Model\Session\Answer;
use App\Domain\Model\Session\Question;
use App\Domain\Repository\Session\AnswerRepositoryInterface;
use App\Domain\Repository\Session\QuestionRepositoryInterface;

class QuizHandler
{
    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /** @var AnswerRepositoryInterface */
    private $answerRepository;

    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param QuestionRepositoryInterface $questionRepository
     * @param AnswerRepositoryInterface   $answerRepository
     * @param \DateTimeInterface          $dateTime
     */
    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        AnswerRepositoryInterface $answerRepository,
        \DateTimeInterface $dateTime
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Quiz $command
     */
    public function handle(Quiz $command)
    {
        $this->questionRepository->remove($command->originalQuestions);

        foreach ($command->questions as $questionAsArray) {
            $question = new Question($questionAsArray['title'], $command->session, $this->dateTime);
            $this->questionRepository->add($question);

            foreach ($questionAsArray['answers'] as $answerAsArray) {
                $answer = new Answer($answerAsArray['title'], $answerAsArray['correct'], $question, $this->dateTime);

                $this->answerRepository->add($answer);
            }
        }
    }
}
