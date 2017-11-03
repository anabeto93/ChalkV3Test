<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\Session;

use App\Domain\Model\Session\Answer;
use App\Domain\Repository\Session\AnswerRepositoryInterface;
use Doctrine\ORM\EntityManager;

class AnswerRepository implements AnswerRepositoryInterface
{
    /** @var EntityManager */
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
    public function add(Answer $answer)
    {
        $this->entityManager->persist($answer);
        $this->entityManager->flush($answer);
    }
}
