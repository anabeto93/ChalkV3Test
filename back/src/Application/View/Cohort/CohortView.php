<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:31
 */

namespace App\Application\View\Cohort;


class CohortView {
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var int */
    public $numberOfStudents;

    /**
     * CohortView constructor.
     * @param int $id
     * @param string $title
     * @param int $numberOfStudents
     */
    public function __construct(int $id, string $title, int $numberOfStudents) {
        $this->id = $id;
        $this->title = $title;
        $this->numberOfStudents = $numberOfStudents;
    }
}