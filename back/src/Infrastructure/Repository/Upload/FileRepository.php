<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\Upload;

use App\Domain\Model\Upload\File;
use App\Domain\Repository\Upload\FileRepositoryInterface;
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
     * {@inheritdoc}
     */
    public function add(File $file)
    {
        $this->entityManager->persist($file);
        $this->entityManager->flush($file);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(File $file)
    {
        $this->entityManager->remove($file);
        $this->entityManager->flush();
    }
}
