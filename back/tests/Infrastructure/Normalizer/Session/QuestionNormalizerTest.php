<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace Tests\Infrastructure\Normalizer\Session;

use App\Domain\Model\Session\Answer;
use App\Domain\Model\Session\Question;
use App\Infrastructure\Normalizer\Session\AnswerNormalizer;
use App\Infrastructure\Normalizer\Session\QuestionNormalizer;
use PHPUnit\Framework\TestCase;

class QuestionNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $question = $this->prophesize(Question::class);
        $answer1 = $this->prophesize(Answer::class);
        $answer2 = $this->prophesize(Answer::class);

        $question->getTitle()->willReturn('question title');
        $question->getAnswers()->willReturn([$answer1->reveal(), $answer2->reveal()]);

        $answerNormalizer = $this->prophesize(AnswerNormalizer::class);
        $answerNormalizer->normalize($answer1->reveal())->shouldBeCalled()->willReturn(['title' => 'title1']);
        $answerNormalizer->normalize($answer2->reveal())->shouldBeCalled()->willReturn(['title' => 'title2']);

        $normalizer = new QuestionNormalizer($answerNormalizer->reveal());
        $result = $normalizer->normalize($question->reveal());

        $expected = [
            'title' => 'question title',
            'answers' => [
                ['title' => 'title1'],
                ['title' => 'title2'],
            ]
        ];

        $this->assertEquals($expected, $result);
    }
}
