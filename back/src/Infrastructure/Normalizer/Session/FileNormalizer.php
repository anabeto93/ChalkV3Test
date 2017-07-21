<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer\Session;

use App\Domain\Model\Session\File;
use App\Infrastructure\Service\UrlGenerator;

class FileNormalizer
{
    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param File $file
     *
     * @return array
     */
    public function normalize(File $file): array
    {
        return [
            'url' => sprintf('%s%s', $this->urlGenerator->getBaseUrl(), $file->getPath()),
            'size' => $file->getSize(),
            'createdAt' => $file->getCreatedAt(),
            'updatedAt' => $file->getUpdatedAt(),
        ];
    }
}
