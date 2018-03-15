<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:25
 */

namespace App\Domain\Repository;


use App\Domain\Model\Cohort;

interface CohortRepositoryInterface {
    /**
     * @param Cohort $cohort
     */
    public function add(Cohort $cohort);

    /**
     * @param Cohort $cohort
     */
    public function set(Cohort $cohort);

    /**
     * @param Cohort $cohort
     */
    public function remove(Cohort $cohort);

    /**
     * @param $institution
     *
     * @return Cohort[]
     */
    public function findByInstitution($institution): array;
}