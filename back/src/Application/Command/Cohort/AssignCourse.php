<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 18/03/2018
 * Time: 19:13
 */

namespace App\Application\Command\Cohort;


use App\Application\Command\Command;
use App\Domain\Model\Cohort;
use App\Domain\Model\Course;

class AssignCourse implements Command {
    /** @var Cohort */
    public $cohort;

    /** @var Course[] */
    public $courses;

    /**
     * AssignCourse constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
        $this->courses = $cohort->getCourses();
    }
}