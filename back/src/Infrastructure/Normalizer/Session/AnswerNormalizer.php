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

class AnswerNormalizer
{
    /**
     * @param Answer $answer
     *
     * @return array
     */
    public function normalize(Answer $answer): array
    {
        return [
            'title' => $answer->getTitle(),
        ];
    }
}
