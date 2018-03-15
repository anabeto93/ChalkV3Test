<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:27
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Repository\CohortRepositoryInterface;
use Doctrine\ORM\EntityManager;

class CohortRepository implements CohortRepositoryInterface {
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
    public function add(Cohort $cohort)
    {
        $this->entityManager->persist($cohort);
        $this->entityManager->flush($cohort);
    }

    /**
     * {@inheritdoc}
     */
    public function set(Cohort $cohort)
    {
        $this->entityManager->flush($cohort);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Cohort $cohort)
    {
        $this->entityManager->remove($cohort);
        $this->entityManager->flush($cohort);
    }

    /**
     * {@inheritdoc}
     */
    public function findByInstitution($institution): array
    {
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->select('cohort')
            ->from(Cohort::class, 'cohort')
            ->where('cohort.institution = :institution')
            ->setParameter('institution', $institution)
            ->orderBy('cohort.title', 'ASC')
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}