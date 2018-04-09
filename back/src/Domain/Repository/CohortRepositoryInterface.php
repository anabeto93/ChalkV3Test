<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:25
 */

namespace App\Domain\Repository;


use App\Domain\Model\Cohort;
use App\Domain\Model\Institution;

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
     * @param Institution $institution
     *
     * @return Cohort[]
     */
    public function findByInstitution(Institution $institution): array;

    /**
     * @param Institution $institution
     * @param string $title
     * @return Cohort|null
     */
    public function findByInstitutionAndTitle(Institution $institution, string $title): ?Cohort;
}