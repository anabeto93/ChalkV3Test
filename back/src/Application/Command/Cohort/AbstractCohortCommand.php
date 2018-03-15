<?php
/**
 * Created by PhpStorm.
 * User: niiapa
 * Date: 15/03/2018
 * Time: 13:49
 */

namespace App\Application\Command\Cohort;


use App\Application\Command\Command;

class AbstractCohortCommand implements Command {
    /** @var string */
    public $title;
}