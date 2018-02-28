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
    private $id;

    /** @var string */
    private $name;

    /** @var int */
    private $numberOfStudents;

    /**
     * InstitutionView constructor.
     * @param int $id
     * @param string $name
     * @param int $numberOfStudents
     */
    public function __construct(int $id, string $name, int $numberOfStudents = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->numberOfStudents = $numberOfStudents;
    }


}