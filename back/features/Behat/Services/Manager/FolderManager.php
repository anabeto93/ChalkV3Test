<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace Features\Behat\Services\Manager;

use App\Domain\Model\Course;
use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;

class FolderManager
{
    /** @var FolderRepositoryInterface */
    private $folderRepository;

    /**
     * @param FolderRepositoryInterface $folderRepository
     */
    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    /**
     * @param string $uuid
     * @param int    $rank
     * @param string $folderTitle
     * @param Course $course
     *
     * @return Folder
     */
    public function create(
        string $uuid,
        int $rank,
        string $folderTitle,
        Course $course
    ): Folder {
        $folder = new Folder($uuid, $rank, $folderTitle, $course, new \DateTime());

        $this->folderRepository->add($folder);

        return $folder;
    }
}
