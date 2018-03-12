<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 02/03/2018
 * Time: 17:02
 */

namespace App\Application\Query\Institution\UserInstitution;


use App\Application\Query\Query;
use App\Domain\Model\Institution;

class UserInstitutionListQuery implements Query {
    /** @var Institution */
    public $institution;

    /**
     * UserInstitutionListQuery constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
    }
}