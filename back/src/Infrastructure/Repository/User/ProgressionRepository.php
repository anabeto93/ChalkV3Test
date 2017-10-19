<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\User;

use App\Domain\Model\Course;
use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\Progression;
use App\Domain\Repository\User\ProgressionRepositoryInterface;
use Doctrine\ORM\EntityManager;

class ProgressionRepository implements ProgressionRepositoryInterface
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
    public function findByUserAndSession(User $user, Session $session): ?Progression
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('progression')
            ->from(Progression::class, 'progression')
            ->where('progression.user = :user')
            ->andWhere('progression.session = :session')
            ->setParameter('user', $user)
            ->setParameter('session', $session)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function add(Progression $progression)
    {
        $this->entityManager->persist($progression);
        $this->entityManager->flush($progression);
    }

    /**
     * {@inheritdoc}
     */
    public function findForUserAndCourse(User $user, Course $course): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('progression')
            ->from(Progression::class, 'progression')
            ->join('progression.session', 'session', 'WITH', 'progression.user = :user AND session.course = :course')
            ->setParameter('user', $user)
            ->setParameter('course', $course)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function countUserForSession(Session $session): int
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('COUNT(IDENTITY(progression))')
            ->from(Progression::class, 'progression')
            ->where('progression.session = :session')
            ->setParameter('session', $session)
        ;

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findForSession(Session $session): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('progression, user')
            ->from(Progression::class, 'progression')
            ->join('progression.user', 'user', 'WITH', 'progression.session = :session')
            ->setParameter('session', $session)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
