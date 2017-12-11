<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer\Session;

use App\Domain\Model\Session\Answer;
use App\Domain\Model\Session\Question;

class QuestionNormalizer
{
    /** @var AnswerNormalizer */
    private $answerNormalizer;

    /**
     * @param AnswerNormalizer $answerNormalizer
     */
    public function __construct(AnswerNormalizer $answerNormalizer)
    {
        $this->answerNormalizer = $answerNormalizer;
    }

    /**
     * @param Question $question
     *
     * @return array
     */
    public function normalize(Question $question): array
    {
        return [
            'title' => $question->getTitle(),
            'isMultiple' => $question->isMultiple(),
            'answers' => array_map(function (Answer $answer) {
                return $this->answerNormalizer->normalize($answer);
            }, $question->getAnswers()),
        ];
    }
}
