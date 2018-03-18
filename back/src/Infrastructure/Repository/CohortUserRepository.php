<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 16:06
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Model\CohortUser;
use App\Domain\Repository\CohortUserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CohortUserRepository implements CohortUserRepositoryInterface {
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * CohortUserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function countUserForCohort(Cohort $cohort): int {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('COUNT(IDENTITY(cohort_user))')
                        ->from(CohortUser::class, 'cohort_user')
                        ->where('cohort_user.cohort = :cohort')
                        ->setParameter('cohort', $cohort);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function findByCohort(Cohort $cohort): array {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('cohort_user, user')
                        ->from(CohortUser::class, 'cohort_user')
                        ->join('cohort_user.user', 'user', 'WITH', 'cohort_user.cohort = :cohort')
                        ->setParameter('cohort', $cohort);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @inheritdoc
     */
    public function remove(CohortUser $cohortUser) {
        $this->entityManager->remove($cohortUser);
        $this->entityManager->flush();
    }
}