<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Domain\Quiz\Result;

use App\Domain\Model\Session;
use App\Domain\Model\Session\Answer;
use App\Domain\Model\Session\Question;
use App\Domain\Quiz\Answers\Views\QuestionView;
use App\Domain\Quiz\Answers\Views\QuizAnswerView;
use App\Domain\Quiz\Result\QuizResultCalculator;
use App\Domain\Quiz\Result\Views\QuizResultView;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class QuizResultCalculatorTest extends TestCase
{
    public function testTransform()
    {
        $session = $this->prophesize(Session::class);

        $question0        = $this->prophesize(Question::class);
        $question0answer0 = $this->prophesize(Answer::class);
        $question0answer0->isCorrect()->shouldBeCalled()->willReturn(false);
        $question0answer1 = $this->prophesize(Answer::class);
        $question0answer1->isCorrect()->shouldBeCalled()->willReturn(true);
        $question0
            ->getAnswers()
            ->shouldBeCalled()
            ->willReturn(
                [
                    $question0answer0->reveal(),
                    $question0answer1->reveal(),
                ]
            )
        ;

        $question1 = $this->prophesize(Question::class);
        $question1answer0 = $this->prophesize(Answer::class);
        $question1answer0->isCorrect()->shouldBeCalled()->willReturn(false);
        $question1answer1 = $this->prophesize(Answer::class);
        $question1answer1->isCorrect()->shouldBeCalled()->willReturn(true);
        $question1answer2 = $this->prophesize(Answer::class);
        $question1answer2->isCorrect()->shouldBeCalled()->willReturn(false);
        $question1answer3 = $this->prophesize(Answer::class);
        $question1answer3->isCorrect()->shouldBeCalled()->willReturn(true);
        $question1answer4 = $this->prophesize(Answer::class);
        $question1answer4->isCorrect()->shouldBeCalled()->willReturn(false);
        $question1
            ->getAnswers()
            ->shouldBeCalled()
            ->willReturn(
                [
                    $question1answer0->reveal(),
                    $question1answer1->reveal(),
                    $question1answer2->reveal(),
                    $question1answer3->reveal(),
                    $question1answer4->reveal(),
                ]
            )
        ;

        $question2 = $this->prophesize(Question::class);
        $question2answer0 = $this->prophesize(Answer::class);
        $question2answer0->isCorrect()->shouldBeCalled()->willReturn(false);
        $question2answer1 = $this->prophesize(Answer::class);
        $question2answer1->isCorrect()->shouldBeCalled()->willReturn(true);
        $question2answer2 = $this->prophesize(Answer::class);
        $question2answer2->isCorrect()->shouldBeCalled()->willReturn(false);
        $question2
            ->getAnswers()
            ->shouldBeCalled()
            ->willReturn(
                [
                    $question2answer0->reveal(),
                    $question2answer1->reveal(),
                    $question2answer2->reveal(),
                ]
            )
        ;

        $question3 = $this->prophesize(Question::class);
        $question3answer0 = $this->prophesize(Answer::class);
        $question3answer0->isCorrect()->shouldBeCalled()->willReturn(true);
        $question3answer1 = $this->prophesize(Answer::class);
        $question3answer1->isCorrect()->shouldBeCalled()->willReturn(false);
        $question3answer2 = $this->prophesize(Answer::class);
        $question3answer2->isCorrect()->shouldBeCalled()->willReturn(true);
        $question3answer3 = $this->prophesize(Answer::class);
        $question3answer3->isCorrect()->shouldBeCalled()->willReturn(false);
        $question3answer4 = $this->prophesize(Answer::class);
        $question3answer4->isCorrect()->shouldBeCalled()->willReturn(false);
        $question3
            ->getAnswers()
            ->shouldBeCalled()
            ->willReturn(
                [
                    $question3answer0->reveal(),
                    $question3answer1->reveal(),
                    $question3answer2->reveal(),
                    $question3answer3->reveal(),
                    $question3answer4->reveal(),
                ]
            )
        ;

        $questionRepository = $this->prophesize(QuestionRepositoryInterface::class);

        $questionRepository
            ->getQuestionsOfSession($session->reveal())
            ->shouldBeCalled()
            ->willReturn([$question0->reveal(), $question1->reveal(), $question2->reveal(), $question3->reveal()])
        ;

        $quizAnswerView = new QuizAnswerView(
            [
                new QuestionView([0]), // incorrect, waiting 1
                new QuestionView([1, 3]), // correct
                new QuestionView([1]), // correct
                new QuestionView([1, 2]), // incorrect, waiting 0, 2
            ]
        );

        $quizResultCalculator = new QuizResultCalculator($questionRepository->reveal());
        $quizResultView = $quizResultCalculator->getQuizResultView($session->reveal(), $quizAnswerView);

        $expectedQuizResultView = new QuizResultView(2, 4);
        $this->assertEquals($expectedQuizResultView, $quizResultView);
    }
}
