<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:04
 */

namespace App\Application\Query\Cohort\CohortUser;


use App\Application\Query\Query;
use App\Domain\Model\Cohort;

class CohortUserListQuery implements Query {
    /** @var Cohort */
    public $cohort;

    /**
     * CohortUserListQuery constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
    }
}