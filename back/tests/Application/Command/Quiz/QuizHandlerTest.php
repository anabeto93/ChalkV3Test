<?php

/*
 * This file is part of the back project.
 *
 * Copyright (C) back
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Application\Command\Quiz;

use App\Application\Command\Quiz\Quiz;
use App\Application\Command\Quiz\QuizHandler;
use App\Domain\Model\Session;
use App\Domain\Repository\Session\AnswerRepositoryInterface;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class QuizHandlerTest extends TestCase
{
    public function testHandle()
    {
        // Init
        $dateTime = new \DateTime();
        $session = $this->prophesize(Session::class);
        $questions = [
            new Session\Question('title1', $session->reveal(), $dateTime),
            new Session\Question('title2', $session->reveal(), $dateTime),
        ];

        $question1 = new Session\Question('new title 1', $session->reveal(), $dateTime);
        $question2 = new Session\Question('new title 2', $session->reveal(), $dateTime);

        $answer1 = new Session\Answer('aTitle1', true, $question1, $dateTime);
        $answer2 = new Session\Answer('aTitle2', false, $question1, $dateTime);
        $answer3 = new Session\Answer('aTitle3', false, $question2, $dateTime);
        $answer4 = new Session\Answer('aTitle4', true, $question2, $dateTime);
        $answer5 = new Session\Answer('aTitle5', true, $question2, $dateTime);

        // Mock
        $questionRepository = $this->prophesize(QuestionRepositoryInterface::class);
        $answerRepository = $this->prophesize(AnswerRepositoryInterface::class);

        $questionRepository->remove($questions)->shouldBeCalled();
        $questionRepository->add($question1)->shouldBeCalled();
        $questionRepository->add($question2)->shouldBeCalled();

        $answerRepository->add($answer1)->shouldBeCalled();
        $answerRepository->add($answer2)->shouldBeCalled();
        $answerRepository->add($answer3)->shouldBeCalled();
        $answerRepository->add($answer4)->shouldBeCalled();
        $answerRepository->add($answer5)->shouldBeCalled();

        // Context
        $quiz = new Quiz($session->reveal(), $questions);
        $quiz->questions = [
            [
                'title' => 'new title 1',
                'answers' => [
                    [
                        'title' => 'aTitle1',
                        'correct' => true,
                    ],
                    [
                        'title' => 'aTitle2',
                        'correct' => false,
                    ],
                ],
            ],
            [
                'title' => 'new title 2',
                'answers' => [
                    [
                        'title' => 'aTitle3',
                        'correct' => false,
                    ],
                    [
                        'title' => 'aTitle4',
                        'correct' => true,
                    ],
                    [
                        'title' => 'aTitle5',
                        'correct' => true,
                    ],
                ],
            ],
        ];

        $handler = new QuizHandler($questionRepository->reveal(), $answerRepository->reveal(), $dateTime);
        $handler->handle($quiz);
    }
}
