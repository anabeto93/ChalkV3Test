<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 05/03/2018
 * Time: 12:13
 */

namespace App\Application\Command\UserInstitution;


use App\Domain\Model\UserInstitution;

class Delete extends AbstractUserInstitutionCommand {
    /** @var UserInstitution */
    public $userInstitution;

    /**
     * Delete constructor.
     * @param UserInstitution $userInstitution
     */
    public function __construct(UserInstitution $userInstitution) {
        $this->userInstitution = $userInstitution;
    }
}