<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Command\Folder;

use App\Domain\Repository\FolderRepositoryInterface;

class DeleteHandler
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
     * @param Delete $command
     */
    public function handle(Delete $command)
    {
        $this->folderRepository->remove($command->folder);
    }
}
