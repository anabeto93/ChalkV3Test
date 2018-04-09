<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:33
 */

namespace App\Application\Query\Cohort;


use App\Application\Query\Query;
use App\Domain\Model\Institution;

class CohortListQuery implements Query {
    /** @var Institution */
    public $institution;

    /**
     * CohortListQuery constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
    }
}