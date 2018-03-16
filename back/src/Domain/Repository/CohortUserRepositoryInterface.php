<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 16:02
 */

namespace App\Domain\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Model\CohortUser;

interface CohortUserRepositoryInterface {
    /**
     * @param Cohort $cohort
     * @return int
     */
    public function counterUserForCohort(Cohort $cohort): int;

    /**
     * @param Cohort $cohort
     * @return CohortUser[]
     */
    public function findByCohort(Cohort $cohort): array;

    /**
     * @param CohortUser $cohortUser
     * @return mixed
     */
    public function remove(CohortUser $cohortUser);
}