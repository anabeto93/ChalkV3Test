<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 19/03/2018
 * Time: 01:29
 */

namespace App\Application\Command\CohortCourse;


use App\Domain\Model\CohortCourse;

class Delete extends AbstractCohortCourseCommand {
    /** @var CohortCourse */
    public $cohortCourse;

    /**
     * Delete constructor.
     * @param CohortCourse $cohortCourse
     */
    public function __construct(CohortCourse $cohortCourse) {
        $this->cohortCourse = $cohortCourse;
    }
}