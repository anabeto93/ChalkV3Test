<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\Folder;
use App\Domain\Repository\FolderRepositoryInterface;
use Doctrine\ORM\EntityManager;

class FolderRepository implements FolderRepositoryInterface
{
    /**
     * @var EntityManager
     */
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
    public function add(Folder $folder)
    {
        $this->entityManager->persist($folder);
        $this->entityManager->flush($folder);
    }

    /**
     * {@inheritdoc}
     */
    public function set(Folder $folder)
    {
        $this->entityManager->flush($folder);
    }

    /**
     * {@inheritdoc}
     */
    public function findByCourse($course): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('folder')
            ->from(Folder::class, 'folder')
            ->where('folder.course = :course')
            ->setParameter('course', $course)
            ->orderBy('folder.title', 'ASC')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
