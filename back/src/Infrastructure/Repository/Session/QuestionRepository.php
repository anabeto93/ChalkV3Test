<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Infrastructure\Repository\Session;

use App\Domain\Model\Session;
use App\Domain\Model\Session\Question;
use App\Domain\Repository\Session\QuestionRepositoryInterface;
use Doctrine\ORM\EntityManager;

class QuestionRepository implements QuestionRepositoryInterface
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
    public function remove(array $questions)
    {
        $this
            ->entityManager
            ->createQueryBuilder()
            ->delete(Question::class, 'question')
            ->where('question IN (:questions)')
            ->setParameter('questions', $questions)
            ->getQuery()
            ->execute()
        ;

        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function add(Question $question)
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush($question);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuestionsOfSession(Session $session): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('question, answer')
            ->from(Question::class, 'question')
            ->join('question.answers', 'answer', 'WITH', 'question.session = :session')
            ->setParameter('session', $session)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
