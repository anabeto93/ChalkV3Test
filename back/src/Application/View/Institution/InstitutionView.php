<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 28/02/2018
 * Time: 03:11
 */

namespace App\Application\View\Institution;


class InstitutionView {
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var int */
    public $numberOfCohorts;

    /** @var int */
    public $numberOfCourses;

    /** @var int */
    public $numberOfStudents;

    /**
     * InstitutionView constructor.
     * @param int $id
     * @param string $name
     * @param int $numberOfCohorts
     * @param int $numberOfCourses
     * @param int $numberOfStudents
     */
    public function __construct(int $id, string $name, int $numberOfCohorts, int $numberOfCourses, int $numberOfStudents) {
        $this->id = $id;
        $this->name = $name;
        $this->numberOfCohorts = $numberOfCohorts;
        $this->numberOfCourses = $numberOfCourses;
        $this->numberOfStudents = $numberOfStudents;
    }
}