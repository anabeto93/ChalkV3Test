<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 17:51
 */

namespace App\Application\Query\Cohort\CohortCourse;


use App\Application\Query\Query;
use App\Domain\Model\Cohort;

class CohortCourseListQuery implements Query {
    /** @var Cohort */
    public $cohort;

    /**
     * CohortCourseListQuery constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
    }
}