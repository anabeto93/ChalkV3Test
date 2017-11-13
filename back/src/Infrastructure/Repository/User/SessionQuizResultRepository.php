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
}
