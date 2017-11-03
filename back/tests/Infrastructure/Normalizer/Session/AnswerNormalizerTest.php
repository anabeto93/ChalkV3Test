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
use App\Infrastructure\Normalizer\Session\AnswerNormalizer;
use PHPUnit\Framework\TestCase;

class AnswerNormalizerTest extends TestCase
{
    public function testNormalize()
    {
        $answer = $this->prophesize(Answer::class);
        $answer->getTitle()->willReturn('title of the answer');

        $normalizer = new AnswerNormalizer();
        $result = $normalizer->normalize($answer->reveal());

        $this->assertEquals(['title' => 'title of the answer'], $result);
    }
}
