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

    /**
     * InstitutionView constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}