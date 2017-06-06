<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use Doctrine\ORM\EntityManager;

class CourseRepository implements CourseRepositoryInterface
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
    public function getAll()
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('course')
            ->from(Course::class, 'course')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getByUuid(string $uuid)
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('course')
            ->from(Course::class, 'course')
            ->where('course.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
