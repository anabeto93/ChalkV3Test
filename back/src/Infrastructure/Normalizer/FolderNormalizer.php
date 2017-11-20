<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Normalizer;

use App\Domain\Model\Folder;

class FolderNormalizer
{
    /** @var \DateTimeInterface */
    private $dateTime;

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param Folder $folder
     *
     * @return array
     */
    public function normalize(?Folder $folder = null): array
    {
        if (null === $folder) {
            return $this->normalizeDefaultFolder();
        }

        return [
            'uuid' => $folder->getUuid(),
            'title' => $folder->getTitle(),
            'updatedAt' => $folder->getUpdatedAt(),
        ];
    }

    /**
     * @return array
     */
    private function normalizeDefaultFolder(): array
    {
        return [
            'uuid' => Folder::DEFAULT_FOLDER,
            'title' => Folder::DEFAULT_FOLDER,
            'updatedAt' => $this->dateTime,
        ];
    }
}
