<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 01/03/2018
 * Time: 00:51
 */

namespace App\Application\Command\Institution;


use App\Application\Command\Command;

abstract class AbstractInstitutionCommand implements Command {
    /** @var string */
    public $name;
}