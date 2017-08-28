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
use App\Domain\Model\Session;
use App\Domain\Repository\SessionRepositoryInterface;
use Doctrine\ORM\EntityManager;

class SessionRepository implements SessionRepositoryInterface
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
    public function add(Session $session)
    {
        $this->entityManager->persist($session);
        $this->entityManager->flush($session);
    }

    /**
     * {@inheritdoc}
     */
    public function set(Session $session)
    {
        $this->entityManager->flush($session);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Session $session)
    {
        $this->entityManager->remove($session);
        $this->entityManager->flush($session);
    }

    /**
     * {@inheritdoc}
     */
    public function findByCourse(Course $course): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('session, folder')
            ->from(Session::class, 'session')
            ->leftJoin('session.folder', 'folder')
            ->where('session.course = :course')
            ->setParameter('course', $course)
            ->orderBy('folder.title')
            ->addOrderBy('session.rank')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getByUuid(?string $uuid): ?Session
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('session')
            ->from(Session::class, 'session')
            ->where('session.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
