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
     * @param string $folderTitle
     * @param Course $course
     *
     * @return Folder
     */
    public function create(
        string $uuid,
        string $folderTitle,
        Course $course
    ): Folder {
        $session = new Folder($uuid, $folderTitle, $course, new \DateTime());

        $this->folderRepository->add($session);

        return $session;
    }
}
