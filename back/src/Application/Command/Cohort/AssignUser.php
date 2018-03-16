<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 16/03/2018
 * Time: 17:32
 */

namespace App\Application\Command\Cohort;


use App\Application\Command\Command;
use App\Domain\Model\Cohort;
use App\Domain\Model\User;

class AssignUser implements Command {
    /** @var Cohort */
    public $cohort;

    /** @var User[] */
    public $users;

    /**
     * AssignUser constructor.
     * @param Cohort $cohort
     */
    public function __construct(Cohort $cohort) {
        $this->cohort = $cohort;
        $this->users = $cohort->getUsers();
    }
}