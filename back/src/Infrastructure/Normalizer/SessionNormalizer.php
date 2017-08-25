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
use App\Infrastructure\Normalizer\Session\FileNormalizer;

class SessionNormalizer
{
    /** @var FileNormalizer */
    private $fileNormalizer;

    /**
     * @param FileNormalizer $fileNormalizer
     */
    public function __construct(FileNormalizer $fileNormalizer)
    {
        $this->fileNormalizer = $fileNormalizer;
    }

    /**
     * @param Session $session
     * @param bool    $isValidated
     *
     * @return array
     */
    public function normalize(Session $session, bool $isValidated = false): array
    {
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
        ];
    }
}
