<?php

/*
 * This file is part of the ChalkboardEducation Application project.
 *
 * Copyright (C) ChalkboardEducation
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository;

use App\Domain\Model\Course;
use App\Domain\Model\User;
use App\Domain\Pagination\PaginatedResult;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class UserRepository implements UserRepositoryInterface
{
    /** @var EntityManager */
    private $entityManager;

    /** @var Paginator */
    private $paginator;

    /**
     * @param EntityManager $entityManager
     * @param Paginator     $paginator
     */
    public function __construct(EntityManager $entityManager, Paginator $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }

    /**
     * {@inheritdoc}
     */
    public function set(User $user)
    {
        $this->entityManager->flush($user);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(int $page, int $limit): PaginatedResult
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user', 'user.id')
            ->orderBy('user.lastName', 'ASC')
            ->addOrderBy('user.firstName', 'ASC');

        return $this->paginator->paginate($queryBuilder, $page, $limit, 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->orderBy('user.lastName', 'ASC')
            ->addOrderBy('user.firstName', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdsIndexedById(array $ids): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user', 'user.id')
            ->where('user.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('user.lastName', 'ASC')
            ->addOrderBy('user.firstName', 'ASC')
        ;

        return $queryBuilder->getQuery()->getResult();
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

    /**
     * {@inheritdoc}
     */
    public function getPhoneNumbers(): array
    {
        $queryBuilder = $this
            ->entityManager
            ->createQueryBuilder()
            ->select('user.phoneNumber')
            ->from(User::class, 'user', 'user.phoneNumber')
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
