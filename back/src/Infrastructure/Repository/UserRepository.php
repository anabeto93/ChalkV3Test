<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class UserRepository implements UserRepositoryInterface
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
    public function findByApiToken(string $apiToken = null): ?User
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.apiToken = :apiToken')
            ->setParameter('apiToken', $apiToken)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserNameByApiToken(string $apiToken = null): ?string
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user.phoneNumber')
            ->from(User::class, 'user')
            ->where('user.apiToken = :apiToken')
            ->setParameter('apiToken', $apiToken)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * {@inheritdoc}
     */
    public function findByPhoneNumber(string $phoneNumber = null): ?User
    {
        if ($phoneNumber === null) {
            return null;
        }

        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.phoneNumber = :phoneNumber')
            ->setParameter('phoneNumber', $phoneNumber)
            ->setMaxResults(1)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
