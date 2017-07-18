<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository;

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
}
