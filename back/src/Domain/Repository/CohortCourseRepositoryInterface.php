<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 17:03
 */

namespace App\Domain\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Model\CohortCourse;

interface CohortCourseRepositoryInterface {

    /**
     * @param Cohort $cohort
     * @return int
     */
    public function countCourseForCohort(Cohort $cohort): int;

    /**
     * @param Cohort $cohort
     * @return CohortCourse[]
     */
    public function findByCohort(Cohort $cohort): array;

    /**
     * @param CohortCourse $cohortCourse
     */
    public function remove(CohortCourse $cohortCourse);
}