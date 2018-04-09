<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 18:46
 */

namespace App\Application\Command\CohortUser;


use App\Domain\Model\CohortUser;

class Delete extends AbstractCohortUserCommand {
    /** @var CohortUser */
    public $cohortUser;

    /**
     * Delete constructor.
     * @param CohortUser $cohortUser
     */
    public function __construct(CohortUser $cohortUser) {
        $this->cohortUser = $cohortUser;
    }
}