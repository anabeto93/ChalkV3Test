<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer;

use App\Domain\Model\Session;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use App\Infrastructure\Normalizer\Session\FileNormalizer;
use App\Infrastructure\Normalizer\Session\QuestionNormalizer;

class SessionNormalizer
{
    /** @var FileNormalizer */
    private $fileNormalizer;

    /** @var QuestionRepositoryInterface */
    private $questionRepository;

    /** @var QuestionNormalizer */
    private $questionNormalizer;

    /**
     * @param FileNormalizer              $fileNormalizer
     * @param QuestionRepositoryInterface $questionRepository
     * @param QuestionNormalizer          $questionNormalizer
     */
    public function __construct(
        FileNormalizer $fileNormalizer,
        QuestionRepositoryInterface $questionRepository,
        QuestionNormalizer $questionNormalizer
    ) {
        $this->fileNormalizer = $fileNormalizer;
        $this->questionRepository = $questionRepository;
        $this->questionNormalizer = $questionNormalizer;
    }

    /**
     * @param Session $session
     * @param bool    $isValidated
     *
     * @return array
     */
    public function normalize(Session $session, bool $isValidated = false): array
    {
        $questions = $this->questionRepository->getQuestionsOfSession($session);

        return [
            'uuid' => $session->getUuid(),
            'rank' => $session->getRank(),
            'title' => $session->getTitle(),
            'content' => $session->getContent(),
            'contentUpdatedAt' => $session->getContentUpdatedAt(),
            'createdAt' => $session->getCreatedAt(),
            'updatedAt' => $session->getUpdatedAt(),
            'validated' => $isValidated,
            'needValidation' => $session->needValidation(),
            'files' => array_map(function (Session\File $file) {
                return $this->fileNormalizer->normalize($file);
            }, $session->getFiles()),
            'questions' => array_map(function (Session\Question $question) {
                return $this->questionNormalizer->normalize($question);
            }, $questions),
        ];
    }
}
