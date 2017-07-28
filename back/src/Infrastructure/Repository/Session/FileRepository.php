<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\Session;

use App\Domain\Model\Session\File;
use App\Domain\Repository\Session\FileRepositoryInterface;
use Doctrine\ORM\EntityManager;

class FileRepository implements FileRepositoryInterface
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param File $file
     */
    public function add(File $file)
    {
        $this->entityManager->persist($file);
        $this->entityManager->flush($file);
    }
}
