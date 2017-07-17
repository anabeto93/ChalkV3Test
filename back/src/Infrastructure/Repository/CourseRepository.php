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
use Doctrine\ORM\EntityManagerInterface;

class CourseRepository implements CourseRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Course $course)
    {
        $this->entityManager->persist($course);
        $this->entityManager->flush($course);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
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
    public function getByUuid(string $uuid): ?Course
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('course, session, folder')
            ->from(Course::class, 'course')
            ->leftJoin('course.sessions', 'session')
            ->leftJoin('session.folder', 'folder')
            ->where('course.uuid = :uuid')
            ->setParameter('uuid', $uuid)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function paginate($offset, $limit): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('course, session, folder')
            ->from(Course::class, 'course')
            ->leftJoin('course.sessions', 'session')
            ->leftJoin('session.folder', 'folder')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
