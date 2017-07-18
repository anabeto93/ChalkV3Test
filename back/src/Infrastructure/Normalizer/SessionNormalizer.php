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

class SessionNormalizer
{
    /**
     * @param Session $session
     *
     * @return array
     */
    public function normalize(Session $session): array
    {
        return [
            'uuid' => $session->getUuid(),
            'title' => $session->getTitle(),
            'content' => $session->getContent(),
            'createdAt' => $session->getCreatedAt(),
            'updatedAt' => $session->getUpdatedAt(),
        ];
    }
}
