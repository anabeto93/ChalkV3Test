<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 02/03/2018
 * Time: 00:57
 */

namespace App\Application\Command\Institution;


use App\Application\Command\Command;
use App\Domain\Model\Institution;
use App\Domain\Model\User;

class AssignUser implements Command {
    /** @var Institution */
    public $institution;

    /** @var User[] */
    public $users;

    /**
     * AssignUser constructor.
     * @param Institution $institution
     */
    public function __construct(Institution $institution) {
        $this->institution = $institution;
        $this->users = $institution->getUsers();
    }
}