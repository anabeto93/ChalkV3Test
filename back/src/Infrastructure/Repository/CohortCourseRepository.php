<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 17:07
 */

namespace App\Infrastructure\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Model\CohortCourse;
use App\Domain\Repository\CohortCourseRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CohortCourseRepository implements CohortCourseRepositoryInterface {
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * CohortCourseRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function countCourseForCohort(Cohort $cohort): int {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('COUNT(IDENTITY(cohort_course))')
                        ->from(CohortCourse::class, 'cohort_course')
                        ->where('cohort_course.cohort = :cohort')
                        ->setParameter('cohort', $cohort);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @inheritdoc
     */
    public function findByCohort(Cohort $cohort): array {
        $queryBuilder = $this->entityManager
                        ->createQueryBuilder()
                        ->select('cohort_course, course')
                        ->from(CohortCourse::class, 'cohort_course')
                        ->join('cohort_course.course', 'course', 'WITH', 'cohort_course.cohort = :cohort')
                        ->setParameter('cohort', $cohort);


        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @inheritdoc
     */
    public function remove(CohortCourse $cohortCourse) {
        $this->entityManager->remove($cohortCourse);
        $this->entityManager->flush();
    }
}