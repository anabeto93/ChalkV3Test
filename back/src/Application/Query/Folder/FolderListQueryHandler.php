<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Application\Query\Folder;

use App\Application\View\Folder\FolderView;
use App\Domain\Repository\FolderRepositoryInterface;

class FolderListQueryHandler
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
     * @param FolderListQuery $query
     *
     * @return FolderView[]
     */
    public function handle(FolderListQuery $query): array
    {
        $folderViews = [];
        $folders = $this->folderRepository->findByCourse($query->course);

        foreach ($folders as $folder) {
            $folderViews[] = new FolderView($folder->getId(), $folder->getTitle());
        }

        return $folderViews;
    }
}
