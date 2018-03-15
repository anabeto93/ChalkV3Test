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

    /**
     * CohortView constructor.
     * @param $id
     * @param $title
     */
    public function __construct($id, $title) {
        $this->id = $id;
        $this->title = $title;
    }
}