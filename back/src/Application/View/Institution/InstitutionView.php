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
    public $numberOfStudents;

    /** @var int */
    public $numberOfCohorts;

    /**
     * InstitutionView constructor.
     * @param int $id
     * @param string $name
     * @param int $numberOfStudents
     * @param int $numberOfCohorts
     */
    public function __construct(int $id, string $name, int $numberOfStudents, int $numberOfCohorts) {
        $this->id = $id;
        $this->name = $name;
        $this->numberOfStudents = $numberOfStudents;
        $this->numberOfCohorts = $numberOfCohorts;
    }
}