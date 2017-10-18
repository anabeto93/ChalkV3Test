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
use App\Domain\Model\UserCourse;
use App\Domain\Repository\UserCourseRepositoryInterface;
use Doctrine\ORM\EntityManager;

class UserCourseRepository implements UserCourseRepositoryInterface
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
    public function countUserForCourse(Course $course): int
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('COUNT(IDENTITY(user_course))')
            ->from(UserCourse::class, 'user_course')
            ->where('user_course.course = :course')
            ->setParameter('course', $course)
        ;

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findByCourse(Course $course): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user_course, user')
            ->from(UserCourse::class, 'user_course')
            ->join('user_course.user', 'user', 'WITH', 'user_course.course = :course')
            ->setParameter('course', $course)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
