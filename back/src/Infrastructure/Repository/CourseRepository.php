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
    public function add(Course $course)
    {
        $this->entityManager->persist($course);
        $this->entityManager->flush($course);
    }

    /**
     * {@inheritdoc}
     */
    public function set(Course $course)
    {
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
            ->select('course, folder, session, userCourses')
            ->from(Course::class, 'course')
            ->leftJoin('course.folders', 'folder')
            ->leftJoin('course.sessions', 'session')
            ->leftJoin('course.userCourses', 'userCourses')
            ->orderBy('course.title')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledCourses(): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('course')
            ->from(Course::class, 'course')
            ->where('course.enabled = true')
            ->orderBy('course.title')
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
}
