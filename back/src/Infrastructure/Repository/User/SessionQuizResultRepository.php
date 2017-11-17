<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\User;

use App\Domain\Model\Session;
use App\Domain\Model\User;
use App\Domain\Model\User\SessionQuizResult;
use App\Domain\Repository\User\SessionQuizResultRepositoryInterface;
use Doctrine\ORM\EntityManager;

class SessionQuizResultRepository implements SessionQuizResultRepositoryInterface
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
    public function findByUserAndSession(User $user, Session $session): ?SessionQuizResult
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('sessionQuizResult')
            ->from(SessionQuizResult::class, 'sessionQuizResult')
            ->where('sessionQuizResult.user = :user')
            ->andWhere('sessionQuizResult.session = :session')
            ->setParameter('user', $user)
            ->setParameter('session', $session)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function add(SessionQuizResult $sessionQuizResult)
    {
        $this->entityManager->persist($sessionQuizResult);
        $this->entityManager->flush($sessionQuizResult);
    }

    /**
     * {@inheritdoc}
     */
    public function countBySession(Session $session): int
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('COUNT(IDENTITY(sessionQuizResult))')
            ->from(SessionQuizResult::class, 'sessionQuizResult')
            ->where('sessionQuizResult.session = :session')
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
            ->select('sessionQuizResult')
            ->from(SessionQuizResult::class, 'sessionQuizResult')
            ->where('sessionQuizResult.session = :session')
            ->setParameter('session', $session)
        ;

        $sessionQuizResults = $queryBuilder->getQuery()->getResult();
        $sessionQuizResultsIndexedByUserId = [];

        /** @var SessionQuizResult[] $sessionQuizResults */
        foreach ($sessionQuizResults as $sessionQuizResult) {
            $sessionQuizResultsIndexedByUserId[$sessionQuizResult->getUser()->getId()] = $sessionQuizResult;
        }

        return $sessionQuizResultsIndexedByUserId;
    }
}
